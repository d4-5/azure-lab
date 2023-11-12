using Azure.Data.Tables;
using Azure.Storage.Blobs;
using System;
using System.Windows.Forms;

namespace Lab3
{
    internal static class Program
    {
        [STAThread]
        static void Main()
        {
            Application.EnableVisualStyles();
            Application.SetCompatibleTextRenderingDefault(false);

            var connectionString = "";
            var tableName = "Contacts";

            var tableClient = new TableClient(connectionString, tableName);
            tableClient.CreateIfNotExists();

            var blobServiceClient = new BlobServiceClient(connectionString);
            var blobContainerClient = blobServiceClient.GetBlobContainerClient("photos");

            var azureBlobStorageService = new AzureBlobStorageService(blobContainerClient);
            var azureTableStorageService = new AzureTableStorageService(tableClient);

            var mainForm = new MainForm(azureBlobStorageService, azureTableStorageService);

            Application.Run(mainForm);
        }
    }
}
