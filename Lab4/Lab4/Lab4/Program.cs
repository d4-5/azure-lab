using Telegram.Bot;
using Telegram.Bot.Args;
using Telegram.Bot.Types.ReplyMarkups;

var builder = WebApplication.CreateBuilder(args);

// Add services to the container.
builder.Services.AddControllers();
builder.Services.AddEndpointsApiExplorer();
builder.Services.AddSwaggerGen();
builder.Services.AddSingleton<ITelegramBotClient>(new TelegramBotClient("6708148816:AAHtcyqQbjjn4W6RS-dg4qXehSTb77V_4TQ"));

var app = builder.Build();

// Configure the HTTP request pipeline.
if (app.Environment.IsDevelopment())
{
    app.UseSwagger();
    app.UseSwaggerUI();
}

app.UseHttpsRedirection();

app.UseRouting();

app.UseAuthorization();

app.UseEndpoints(endpoints =>
{
    endpoints.MapControllers();
});

// Add the following code to handle Telegram updates
var botClient = app.Services.GetRequiredService<ITelegramBotClient>();
botClient.OnMessage += async (sender, e) =>
{
    if (e.Message?.Text != null)
    {
        var chatId = e.Message.Chat.Id;
        var responseText = GetResponseText(e.Message.Text);

        var replyKeyboardMarkup = new ReplyKeyboardMarkup
        {
            Keyboard = new[]
            {
                new[]
                {
                    new KeyboardButton("Спеціальності"),
                    new KeyboardButton("Військова кафедра"),
                },
                new[]
                {
                    new KeyboardButton("Адреса"),
                    new KeyboardButton("Офіційний сайт"),
                },
            },
            ResizeKeyboard = true
        };

        await botClient.SendTextMessageAsync(
            chatId: chatId,
            text: responseText,
            replyMarkup: replyKeyboardMarkup
        );
    }
};

botClient.StartReceiving();

app.Run();

string GetResponseText(string pressedButton)
{
    switch (pressedButton)
    {
        case "Спеціальності":
            return "Міжнародні відносини, суспільні комунікації та регіональні студії(міжнародна інформація) (д)\nПрикладна математика (прикладна математика та інформатика)з елементами дуальної освіти (д)\nПрикладна математика (фінансовий інжиніринг) з елементами дуальної освіти (д)\nПрикладна фізика та наноматеріали (д)";
        case "Військова кафедра":
            return "Присутня";
        case "Адреса":
            return "вул. Митрополита Андрея 5, 4-й н.к., кім. 212а";
        case "Офіційний сайт":
            return "https://lpnu.ua/imfn";
        default:
            return "Виберіть один з пунктів меню";
    }
}
