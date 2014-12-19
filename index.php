<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Pic'Emon au Pic</title>

    <!-- Bootstrap Core CSS -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="css/agency.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='http://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='http://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body id="page-top" class="index">
    <script type="text/javascript">
        function reloadAfterLogin(){
            document.formLogin.submit();
        }
    </script>
<?php
    include 'votes.php';
    require_once 'xmlToArrayParser.class.php';
    $userName = null;
    if (isset($_GET["ticket"])){
        $url = "https://cas.utc.fr/cas/serviceValidate?ticket=".$_GET["ticket"]."&service=http://".$_SERVER['HTTP_HOST']."/picemon";
        $result = file_get_contents($url);
        
        $parsed = new xmlToArrayParser($result);

        
        $userName = $parsed->array['cas:serviceResponse']['cas:authenticationSuccess']['cas:attributes']['cas:givenName'];
        
        $result = $parsed->array['cas:serviceResponse']['cas:authenticationSuccess']['cas:user'];
        ?>
        <form name="formLogin" method="POST" action="index.php">
            <?php
                echo "<input type=\"hidden\" value=\"".$result."\" name=\"loginUTC\"/>";
                echo "<input type=\"hidden\" value=\"".$userName."\" name=\"userName\"/>";
                echo "<script> reloadAfterLogin();</script>"
            ?>
        </form> 
        <?php
    }
    
    if (isset($_POST['voted'])){

        if ($_POST['voted'] == "false"){
            echo "<script> alert('Poulet!'); </script>";
        }
    }

    $userExists = false;
    $userTeam = -1;
    if (isset($_POST['loginUTC'])){
        echo "<script>var isLogged = true;</script>";
        $con = mysqli_connect("localhost", "root", "", "picemon");
        $query = "SELECT * FROM `picemon`.`teams`";
        $res = mysqli_query($con, $query);
        
        while($row = mysqli_fetch_array($res, MYSQL_NUM)){
            if($row[0] == $_POST["loginUTC"]){
                $userExists = true;
                $userTeam = $row[1];
                echo "<script>var userExists = true; var userTeam = ".$row[1].";</script>";

            }

        }

        if ($userExists == false){
            echo "<script>var userExists = false;</script>";
        }

       
    }   

    if (isset($_POST['choiceTeam']) && isset($_POST['loginUTC']) && $userExists == false){
        $con = mysqli_connect("localhost", "root", "", "picemon");
        $query = "INSERT INTO `picemon`.`teams` (login, team) VALUES (\"".$_POST['loginUTC']."\", \"".$_POST['choiceTeam']."\")";
        $res = mysqli_query($con, $query);
    }
    



?>

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header page-scroll">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand page-scroll" href="#page-top">Pic'&Eacute;mon</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav navbar-right">
                    <li class="hidden">
                        <a href="#page-top"></a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#services">Choisis Ta Team</a>
                    </li>
                    <li>
                        <a class="page-scroll" href="#portfolio">Battle des Starters</a>
                    </li>
                  
                    <li>
                        <a class="page-scroll" href="#team">Notre Team</a>
                    </li>
                    <?php
                    if(!isset($_POST['loginUTC'])){
                        echo "
                            <li>
                                <a class=\"page-scroll\" href=\"https://cas.utc.fr/cas/login?service=http://".$_SERVER['HTTP_HOST']."/picemon\">Se Connecter</a>
                            </li>
                        ";
                    }else{
                        if ($_POST['userName'] != null){

                            echo "
                                <li>
                                    <a class=\"page-scroll\" href=\"https://cas.utc.fr/cas/login?service=http://".$_SERVER['HTTP_HOST']."/picemon\">".$_POST['userName']."</a>
                                </li>
                            ";
                        }
                    }

                    ?>
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container-fluid -->
    </nav>

    <!-- Header -->
    <header>
        <div class="container">
            <div class="intro-text">
             <!--    <div class="intro-lead-in">Chauds pour Lundi?!</div>
                <div class="intro-heading">Perm Pic'Emon au Pic!</div> -->
                <img class="img-titleJo" src="img/picemon.png">
                <!-- <a href="#services" class="page-scroll btn btn-xl">Tell Me More</a> -->
            </div>
        </div>
    </header>

    <!-- Services Section -->
    <section id="services">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">T. Rocket VS T. Sasha</h2>
                    <?php
                        if ($userExists == false){

                            echo "<h3 class=\"section-subheading text-muted\">Pour choisir ta team connecte toi grace au CAS, et ensuite il suffit de clicker sur le bouton Choisir!</h3>";

                        }else{
                            $teamTemp = ($userTeam == 0) ? "T. Rocket" : "T. Ash";
                            echo "<h3 class=\"section-subheading text-muted\">T'as choisi <b>".$teamTemp."</b> comme ta team!</h3>";
 
                        }
                    ?>
                </div>
            </div>
            <div class="row text-center">
               
                <div class="col-md-6">
               
                        <img class="img-responsive img-circle img-resizeJo img-centerJo" src="img/teamrocket.jpg" id="rocket" onclick="selectVoteTeamAsh('rocket'); selectToVote(0, 1);"/> <!-- 0 = rocket // 1= ash-->
                        
                    
                    <h4 class="service-heading">Team Rocket</h4>
                    <p class="text-muted">Les bg de la Team Rocket qui seront la pour vous emmerder!</p>
                </div>
                <div class="col-md-6">
                   
                        <img class="img-responsive img-circle img-resizeJo img-centerJo" src="img/ash.jpg" id="ash" onclick="selectVoteTeamAsh('ash'); selectToVote(1, 1);"/>
                 
                    <h4 class="service-heading">Team Sasha</h4>
                    <p class="text-muted">La Team Sasha qui passera son temps a vous animer au Pic!</p>
                </div>
            </div>
        </div>
        <div class="container">
            <form name="teamChoice" action="index.php" method="POST">
                <?php
                    if(isset($_POST['loginUTC'])){
                        echo "<input type=\"hidden\" name=\"loginUTC\" id =\"loginUTC\" value=\"".$_POST['loginUTC']."\" />";        
                    }
                ?>
                <input type="hidden" name="choiceTeam" id ="choiceTeam" value="" />

                <?php
                if($userExists == false){
                    echo "
                        <input type=\"submit\" value=\"Choisir!\" class=\"btn btn-default btn-big\"  />
                    ";
                }

                ?>

            </form>
        </div>
    </section>

    <!-- Portfolio Grid Section -->
    <section id="portfolio" class="bg-light-gray">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Battle of Starters</h2>
                    <h3 class="section-subheading text-muted">Clickez sur votre starter prefere et submittez le vote!</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6 portfolio-item">
                    <img class="img-responsive img-circle img-resizeJo img-centerJo border-Jo" src="img/pokemon/charmander.jpg" id="charmander" onclick="selectVoteStarter('charmander'); selectToVote('charmander', 2);"/>
                </div>
                <div class="col-md-4 col-sm-6 portfolio-item">
                  <img class="img-responsive img-circle img-resizeJo img-centerJo border-Jo" src="img/pokemon/squirtle.png" id="squirtle" onclick="selectVoteStarter('squirtle'); selectToVote('squirtle', 2);"/>
                </div>
                <div class="col-md-4 col-sm-6 portfolio-item">
                    <img class="img-responsive img-circle img-resizeJo img-centerJo border-Jo" src="img/pokemon/bulbasaur.png" id="bulbasaur" onclick="selectVoteStarter('bulbasaur'); selectToVote('bulbasaur', 2);"/>
                </div>
                <div class="col-md-4 col-sm-6 portfolio-item">
                   <img class="img-responsive img-circle img-resizeJo img-centerJo border-Jo" src="img/pokemon/cyndaquil.png" id="cyndaquil" onclick="selectVoteStarter('cyndaquil'); selectToVote('cyndaquil', 2);"/>
                </div>
                <div class="col-md-4 col-sm-6 portfolio-item">
                   <img class="img-responsive img-circle img-resizeJo img-centerJo border-Jo" src="img/pokemon/totodile.png" id="totodile" onclick="selectVoteStarter('totodile'); selectToVote('totodile', 2);"/>
                </div>
                <div class="col-md-4 col-sm-6 portfolio-item">
                 <img class="img-responsive img-circle img-resizeJo img-centerJo border-Jo" src="img/pokemon/chikorita.png" id="chikorita" onclick="selectVoteStarter('chikorita'); selectToVote('chikorita', 2);"/>
                </div>
            </div>
        </div> 
        <div class="container">
            <form id="mainForm" method="POST" action="index.php">
                <input type="hidden" name = "voted" id ="voted" value="false"/>
                
                <input type="hidden" name="voteStarter" id ="voteStarter" value="" />
                
                            <input type="submit" value="Voter!" class="btn btn-default btn-big" /> 
               
            </form>
        </div>
    </section>

    <!-- About Section -->`
   

    <!-- Team Section -->
    <section id="team" class="bg-light-gray">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h2 class="section-heading">Notre Team de Ouf!</h2>
                    <h3 class="section-subheading text-muted">Lorem ipsum dolor sit amet consectetur.</h3>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="img/team/felix.jpg" class="img-responsive img-circle img-resizeJo" alt="">
                        <h4>Felix Ali&eacute;</h4>
                                                <ul class="list-inline social-buttons">
                            
                        </ul>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="img/team/agathe.gif" class="img-responsive img-circle img-resizeJo" alt="">
                        <h4>Agathe Guillemot</h4>
                                                <ul class="list-inline social-buttons">
                            
                        </ul>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="img/team/flore.png" class="img-responsive img-circle img-resizeJo" alt="">
                        <h4>Flore Schmidt</h4>
                        
                        <ul class="list-inline social-buttons">
                            
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="img/team/alic.png" class="img-responsive img-circle img-resizeJo" alt="">
                        <h4>Alice Barrios</h4>
                                                <ul class="list-inline social-buttons">
                            
                        </ul>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="img/team/lea.jpg" class="img-responsive img-circle img-resizeJo" alt="">
                        <h4>Lea Bourg&egrave;s</h4>
                                                <ul class="list-inline social-buttons">
                            
                        </ul>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="img/team/lea.jpg" class="img-responsive img-circle img-resizeJo" alt="">
                        <h4>Nicolas Abdelnour</h4>
                        
                        <ul class="list-inline social-buttons">
                            
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="img/team/mathilde.jpg" class="img-responsive img-circle img-resizeJo" alt="">
                        <h4>Mathilde Longuet</h4>
                                                <ul class="list-inline social-buttons">
                            
                        </ul>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="img/team/jo.gif" class="img-responsive img-circle img-resizeJo" alt="">
                        <h4>Jo Colina</h4>
                                                <ul class="list-inline social-buttons">
                            
                        </ul>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="img/team/clemence.png" class="img-responsive img-circle img-resizeJo" alt="">
                        <h4>Cl&eacute;mence LV</h4>
                        
                        <ul class="list-inline social-buttons">
                            
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="img/team/edouard.png" class="img-responsive img-circle img-resizeJo" alt="">
                        <h4>Edouard Debbie</h4>
                                                <ul class="list-inline social-buttons">
                            
                        </ul>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="img/team/armand.gif" class="img-responsive img-circle img-resizeJo" alt="">
                        <h4>Armand Ghirardini</h4>
                                                <ul class="list-inline social-buttons">
                            
                        </ul>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="img/team/antoine.gif" class="img-responsive img-circle img-resizeJo" alt="">
                        <h4>Antoine Freechild</h4>
                        
                        <ul class="list-inline social-buttons">
                            
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="img/team/quentin.png" class="img-responsive img-circle img-resizeJo" alt="">
                        <h4>Quentin Viallet</h4>
                                                <ul class="list-inline social-buttons">
                            
                        </ul>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="img/team/maxence.png" class="img-responsive img-circle img-resizeJo" alt="">
                        <h4>Maxence Viallet</h4>
                                                <ul class="list-inline social-buttons">
                            
                        </ul>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="img/team/tatiana.png" class="img-responsive img-circle img-resizeJo" alt="">
                        <h4>Tatiana Ricard</h4>
                        
                        <ul class="list-inline social-buttons">
                            
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="img/team/herome.png" class="img-responsive img-circle img-resizeJo" alt="">
                        <h4>Jérome Durand</h4>
                                                <ul class="list-inline social-buttons">
                            
                        </ul>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="img/team/axel.png" class="img-responsive img-circle img-resizeJo" alt="">
                        <h4>Axel Barbosa</h4>
                                                <ul class="list-inline social-buttons">
                            
                        </ul>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="img/team/france.jpg" class="img-responsive img-circle img-resizeJo" alt="">
                        <h4>France BoopBooppidouw'</h4>
                        
                        <ul class="list-inline social-buttons">
                            
                        </ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="img/team/clement.png" class="img-responsive img-circle img-resizeJo" alt="">
                        <h4>Clément Routier</h4>
                                                <ul class="list-inline social-buttons">
                            
                        </ul>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="img/team/gary.png" class="img-responsive img-circle img-resizeJo" alt="">
                        <h4>Gary Magnin</h4>
                                                <ul class="list-inline social-buttons">
                            
                        </ul>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="team-member">
                        <img src="img/team/damien.gif" class="img-responsive img-circle img-resizeJo" alt="">
                        <h4>Damien Delmas</h4>
                        
                        <ul class="list-inline social-buttons">
                            
                        </ul>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-8 col-lg-offset-2 text-center">
                    <p class="large text-muted">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aut eaque, laboriosam veritatis, quos non quis ad perspiciatis, totam corporis ea, alias ut unde.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Clients Aside -->
    <aside class="clients">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-sm-6">
                    <a href="#">
                        <img src="img/logos/envato.jpg" class="img-responsive img-centered" alt="">
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="#">
                        <img src="img/logos/designmodo.jpg" class="img-responsive img-centered" alt="">
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="#">
                        <img src="img/logos/themeforest.jpg" class="img-responsive img-centered" alt="">
                    </a>
                </div>
                <div class="col-md-3 col-sm-6">
                    <a href="#">
                        <img src="img/logos/creative-market.jpg" class="img-responsive img-centered" alt="">
                    </a>
                </div>
            </div>
        </div>
    </aside>

    
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <span class="copyright">Copyright &copy; Pic'Emon | Felix Ali&eacute; | Jo Colina 2014</span>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline social-buttons">
                        <li><a href="#"><i class="fa fa-twitter"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-facebook"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <ul class="list-inline quicklinks">
                        <li><a href="#">Privacy Policy</a>
                        </li>
                        <li><a href="#">Terms of Use</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- Portfolio Modals -->
    <!-- Use the modals below to showcase details about your portfolio projects! -->

    <!-- Portfolio Modal 1 -->
    <div class="portfolio-modal modal fade" id="portfolioModal1" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <!-- Project Details Go Here -->
                            <h2>Project Name</h2>
                            <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                            <img class="img-responsive" src="img/portfolio/roundicons-free.png" alt="">
                            <p>Use this area to describe your project. Lorem ipsum dolor sit amet, consectetur adipisicing elit. Est blanditiis dolorem culpa incidunt minus dignissimos deserunt repellat aperiam quasi sunt officia expedita beatae cupiditate, maiores repudiandae, nostrum, reiciendis facere nemo!</p>
                            <p>
                                <strong>Want these icons in this portfolio item sample?</strong>You can download 60 of them for free, courtesy of <a href="https://getdpd.com/cart/hoplink/18076?referrer=bvbo4kax5k8ogc">RoundIcons.com</a>, or you can purchase the 1500 icon set <a href="https://getdpd.com/cart/hoplink/18076?referrer=bvbo4kax5k8ogc">here</a>.</p>
                            <ul class="list-inline">
                                <li>Date: July 2014</li>
                                <li>Client: Round Icons</li>
                                <li>Category: Graphic Design</li>
                            </ul>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Close Project</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Portfolio Modal 2 -->
    <div class="portfolio-modal modal fade" id="portfolioModal2" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <h2>Project Heading</h2>
                            <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                            <img class="img-responsive img-centered" src="img/portfolio/startup-framework-preview.png" alt="">
                            <p><a href="http://designmodo.com/startup/?u=787">Startup Framework</a> is a website builder for professionals. Startup Framework contains components and complex blocks (PSD+HTML Bootstrap themes and templates) which can easily be integrated into almost any design. All of these components are made in the same style, and can easily be integrated into projects, allowing you to create hundreds of solutions for your future projects.</p>
                            <p>You can preview Startup Framework <a href="http://designmodo.com/startup/?u=787">here</a>.</p>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Close Project</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Portfolio Modal 3 -->
    <div class="portfolio-modal modal fade" id="portfolioModal3" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <!-- Project Details Go Here -->
                            <h2>Project Name</h2>
                            <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                            <img class="img-responsive img-centered" src="img/portfolio/treehouse-preview.png" alt="">
                            <p>Treehouse is a free PSD web template built by <a href="https://www.behance.net/MathavanJaya">Mathavan Jaya</a>. This is bright and spacious design perfect for people or startup companies looking to showcase their apps or other projects.</p>
                            <p>You can download the PSD template in this portfolio sample item at <a href="http://freebiesxpress.com/gallery/treehouse-free-psd-web-template/">FreebiesXpress.com</a>.</p>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Close Project</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Portfolio Modal 4 -->
    <div class="portfolio-modal modal fade" id="portfolioModal4" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <!-- Project Details Go Here -->
                            <h2>Project Name</h2>
                            <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                            <img class="img-responsive img-centered" src="img/portfolio/golden-preview.png" alt="">
                            <p>Start Bootstrap's Agency theme is based on Golden, a free PSD website template built by <a href="https://www.behance.net/MathavanJaya">Mathavan Jaya</a>. Golden is a modern and clean one page web template that was made exclusively for Best PSD Freebies. This template has a great portfolio, timeline, and meet your team sections that can be easily modified to fit your needs.</p>
                            <p>You can download the PSD template in this portfolio sample item at <a href="http://freebiesxpress.com/gallery/golden-free-one-page-web-template/">FreebiesXpress.com</a>.</p>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Close Project</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Portfolio Modal 5 -->
    <div class="portfolio-modal modal fade" id="portfolioModal5" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <!-- Project Details Go Here -->
                            <h2>Project Name</h2>
                            <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                            <img class="img-responsive img-centered" src="img/portfolio/escape-preview.png" alt="">
                            <p>Escape is a free PSD web template built by <a href="https://www.behance.net/MathavanJaya">Mathavan Jaya</a>. Escape is a one page web template that was designed with agencies in mind. This template is ideal for those looking for a simple one page solution to describe your business and offer your services.</p>
                            <p>You can download the PSD template in this portfolio sample item at <a href="http://freebiesxpress.com/gallery/escape-one-page-psd-web-template/">FreebiesXpress.com</a>.</p>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Close Project</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Portfolio Modal 6 -->
    <div class="portfolio-modal modal fade" id="portfolioModal6" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-content">
            <div class="close-modal" data-dismiss="modal">
                <div class="lr">
                    <div class="rl">
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-lg-offset-2">
                        <div class="modal-body">
                            <!-- Project Details Go Here -->
                            <h2>Project Name</h2>
                            <p class="item-intro text-muted">Lorem ipsum dolor sit amet consectetur.</p>
                            <img class="img-responsive img-centered" src="img/portfolio/dreams-preview.png" alt="">
                            <p>Dreams is a free PSD web template built by <a href="https://www.behance.net/MathavanJaya">Mathavan Jaya</a>. Dreams is a modern one page web template designed for almost any purpose. It’s a beautiful template that’s designed with the Bootstrap framework in mind.</p>
                            <p>You can download the PSD template in this portfolio sample item at <a href="http://freebiesxpress.com/gallery/dreams-free-one-page-web-template/">FreebiesXpress.com</a>.</p>
                            <button type="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> Close Project</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- jQuery -->
    <script src="js/jquery.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>
    <script src="js/classie.js"></script>
    <script src="js/cbpAnimatedHeader.js"></script>

    <!-- Contact Form JavaScript -->
    <!-- // <script src="js/jqBootstrapValidation.js"></script> -->
    <!-- // <script src="js/contact_me.js"></script> -->

    <!-- Custom Theme JavaScript -->
    <script src="js/agency.js"></script>
    <script type="text/javascript" src="js/main.js"></script>

</body>

</html>
