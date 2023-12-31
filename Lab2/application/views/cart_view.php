<?php 
function get_content($data)
{
    if (!isset($_POST['Confirm'])) {
        $cart_content = file_get_contents('application/views/templates/cart_content.html');
        if (empty($data)) {
            echo "<h3 class='twelve column'>Your cart is empty</h3>";
        } else {
            echo "<form class='twelve column' action='".baseaddress."/cart/confirm' method='POST'>";
            foreach ($data as $item) {
                require_once "application/core/constant.php";
                $PDO = new PDO("mysql:dbname=".dbname.";host=".dbhost, dbuser, dbpass);
                $select = "SELECT id, title, description, cost_price as price FROM tovar where id = :id";
                $PDOStatement = $PDO->prepare($select);
                $PDOStatement->bindParam(':id', $item[0], PDO::PARAM_INT);
                $res = $PDOStatement->execute();
                $element = $PDOStatement->fetch();

                $photo = Model_Cart::get_photo($element['id']);
                $cartitem = str_replace("##srcimg##", $photo, $cart_content);
                $cartitem = str_replace("##details##", $element['description'], $cartitem);
                $cartitem = str_replace("##id##", $element['id'], $cartitem);
                $cartitem = str_replace("##price##", "$".$element['price'], $cartitem);
                $cartitem = str_replace("##count##", $item[1], $cartitem);
                $cartitem = str_replace("##title##", $element['title'], $cartitem);
                $cartitem = str_replace("##total##", "$".$item[1]*$element['price'], $cartitem);

                echo $cartitem;
            }
            echo "<hr class='twelve column'/>";
            $form = file_get_contents('application/views/templates/order_form.html');
            echo $form;
            echo "</form>";
            ?>
            <script type="text/javascript">
                $("input").change(function() {
                    id = $(this).attr('id');
                    id = id.replace("count", "");
                    var total = $("#price"+id).text();
                    total = total.replace("$", "");
                    total = total * $(this).val();
                    $("#total"+id).text("$" + total.toFixed(2));
                });
            </script>
            <script type="text/javascript">
                $(".deleteItem").click(function(){
                    id = $(this).attr('id');
                    id = id.replace("delete", "");
                    $("#item"+id).load("<?=baseaddress;?>/add_to_basket/delete/"+id);
                });
            </script>
            <?php
        }
    } else {
        print_r($data);
    }
}

function buttons_content()
{
    $button = file_get_contents('application/views/templates/button.html');
    $button = str_replace("##title##", "See all tovar", $button);
    $button = str_replace("##href##", baseaddress."/tovar/", $button);
    echo $button;
}

function get_title()
{
    return "Cart";
}
?>
