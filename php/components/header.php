<html>
<header>
    <title>DungeonXplorer</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="../../script/profileButtonScript.js"></script>
    <script src="https://kit.fontawesome.com/68b987a37a.js" crossorigin="anonymous"></script>
    <style>
        .triangle-left {
            width: 0;
            height: 0;
            border-bottom: 50px solid transparent;
            border-left: 96px solid #941515;
        }

        .triangle-right {
            width: 0;
            height: 0;
            border-bottom: 50px solid transparent;
            border-right: 96px solid #941515;
        }
    </style>
</header>

<body class="bg-[#1A1A1A] min-h-screen flex flex-col">
    <nav class="fixed top-0 left-12">
        <div class="group relative -translate-y-[50%] hover:translate-y-0 transition-all duration-700 ease-in-out cursor-pointer">

            <div class="bg-[#941515] w-48 shadow-lg overflow-hidden">

                <div class="min-h-16 group-hover:min-h-0 transition-all duration-700 ease-in-out overflow-hidden">
                    <?php
                    $menuItems = [
                        ['name' => 'Accueil', 'url' => '/'],
                        ['name' => 'Profil', 'url' => '#'],
                    ];

                    foreach ($menuItems as $item): ?>
                        <a href="<?php echo $item['url']; ?>"
                            class="block px-6 py-3 text-white hover:bg-red-800 transition-all duration-300 opacity-0 group-hover:opacity-100">
                            <?php echo $item['name']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="flex">
                <div class="triangle-left"></div>
                <div class="triangle-right"></div>
            </div>

        </div>
    </nav>
    <div class="absolute right-0 p-[1%]">
        <button class="text-white relative z-10" id="profileButton">
            <i class="fa-regular fa-3x fa-circle-user"></i>
        </button>

        <div style="visibility: hidden;" 
            class="absolute top-full right-0 mr-4 flex flex-col text-white border-solid rounded-md shadow-lg border-[#2B2B2B] bg-[#8B1E1E] border-2 pt-2 pb-2 pl-4 pr-4 z-50 w-max" 
            id="profileText">
            
            <?php
            if (isset($_SESSION['user_id'])) {
                echo "
                    <a href='https://dev-dx01.users.info.unicaen.fr/php/settings.php' class='hover:text-[#f2a900] py-1'>Paramètres</a>
                    <a href='https://dev-dx01.users.info.unicaen.fr/php/connection/logout.php' class='hover:text-[#f2a900] py-1'>Se déconnecter</a>
                    ";
            } else {
                echo "
                    <a href='https://dev-dx01.users.info.unicaen.fr/php/connection/login.php' class='hover:text-[#f2a900] py-1'>Se connecter</a>
                    <a href='https://dev-dx01.users.info.unicaen.fr/php/connection/register.php' class='hover:text-[#f2a900] py-1'>Créer un compte</a>
                ";
            }
            ?>
        </div> 
    </div>
