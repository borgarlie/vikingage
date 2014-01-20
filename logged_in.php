<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">  
<html xmlns="http://www.w3.org/1999/xhtml">
<head>  
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />  
    <title>Viking Age</title>  
    <link href="style.css" rel="stylesheet" type="text/css" />
    <?php
    $unread_numrows = 0; 
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    $stmt = $mysqli->prepare("SELECT isread FROM mails WHERE isread = 0 AND receiver = '" . $_SESSION['user_name'] . "';");
    $stmt->execute();
    $stmt->bind_result($col1);
    while ($stmt->fetch()) {
        $unread_numrows += 1;
    }
    $stmt->close();
    $mysqli->close();
    ?>  
</head>
<body>  
    <div id="main">
        <div class="container">  
            <div id="header">
            </div>
            <div id="left"> 
                <br><b><u>Player Interaction</u></b>
                <br><a href="?page=player">Player</a>
                <?php
                if ($unread_numrows > 0) {
                    echo "<br><a href='?page=mail'>Mail($unread_numrows)</a>";
                }
                else {
                    echo "<br><a href='?page=mail'>Mail</a>";
                }
                ?>
                <br><a href="?page=guild">Guild</a>
                <br><a href="?page=market">Market</a>
                <br><br><b><u>Navigation</u></b>
                <br><a href="?page=city">Your City</a>
                <br><a href="?page=citizencontrol">Citizen Control</a>
                <br><a href="?page=battletraining">Battle Training</a>
                <br><a href="?page=diamondgathering">Diamond Search</a>
                <br><a href="?page=hunting">Hunting</a>
                <br><a href="?page=huntingtraining">Hunting Training</a>
                <br><a href="?page=praying">Pray</a>
                <br><a href="?page=pvp">PvP</a>
                <br><a href="?page=quests">Quest</a>
                <br><a href="?page=shop">Shop</a>
                <br><a href="?page=diamondshop">Diamond Shop</a>
                <br><br><b><u>Other</u></b>
                <br><a href="?page=faq">FAQ</a>
                <br><a href="?page=purchase">Purchase</a>
                <br><a href="?page=rank">Ranking</a>
                <br><a href="http://vikingage.freeforums.org/" target="_blank">Forum</a>
                <br><a href="?page=guildforum">Guild Forum</a>
            </div>
            <div id="middle">  
                <!-- <br> -->
                <?php
                if(isset($_GET['page'])) {
                    $page = $_GET['page'];

                    if($page == "city"){ 
                        include"objectives\city.php"; 
                    } 
                    elseif($page == "battletraining"){ 
                        include"objectives\battletraining.php"; 
                    } 
                    elseif($page == "citizencontrol"){ 
                        include"objectives\citizencontrol.php"; 
                    }
                     elseif($page == "diamondgathering"){ 
                        include"objectives\diamondgathering.php"; 
                    } 
                     elseif($page == "hunting"){ 
                        include"objectives\hunting.php"; 
                    } 
                    elseif($page == "huntingtraining"){ 
                        include"objectives\huntingtraining.php"; 
                    }
                    elseif($page == "praying"){ 
                        include"objectives\praying.php"; 
                    }
                     elseif($page == "pvp"){ 
                        include"objectives\pvp.php"; 
                    } 
                     elseif($page == "quests"){ 
                        include"objectives\quests.php"; 
                    } 
                    elseif($page == "shop"){ 
                        include"objectives\shop.php"; 
                    }
                    elseif($page == "diamondshop"){ 
                        include"objectives\diamondshop.php"; 
                    }
                    elseif($page == "faq"){ 
                        include"other\afaq.php"; 
                    }
                    elseif($page == "guild"){ 
                        include"interaction\guild.php"; 
                    }
                    elseif($page == "mail"){ 
                        include"interaction\mail.php"; 
                    }
                    elseif($page == "market"){ 
                        include"interaction\market.php"; 
                    }
                    elseif($page == "player"){ 
                        include"interaction\player.php"; 
                    }
                    elseif($page == "forum"){ 
                        include"other\aforum.php"; 
                    }
                    elseif($page == "purchase"){ 
                        include"other\purchase.php"; 
                    }
                    elseif($page == "guildforum"){ 
                        include"other\guildforum.php"; 
                    }
                    elseif($page == "rank"){ 
                        include"other\aranking.php"; 
                    }
                    elseif($page == "quest1"){ 
                        include"objectives\quests\quest1.php"; 
                    }
                    elseif($page == "quest2"){ 
                        include"objectives\quests\quest2.php"; 
                    }
                    elseif($page == "quest3"){ 
                        include"objectives\quests\quest3.php"; 
                    }
                    else{ 
                        include"objectives\city.php";
                    }
                }
                else{ 
                        include"objectives\city.php";
                    }
                ?>
            </div>

            <div id="right">
                <?php
                    include"resources.php";
                ?>
            </div>
            <div id="footer">  
                &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp
                Version 0.0.1
            </div>
        </div>
    </div>  
</body>  
</html>

<!-- Sell function, sell citizens to buy soldiers if your under attack alot. -->
<!-- Use battle training to increase level of soldiers? like hunting training -->