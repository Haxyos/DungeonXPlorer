<?php
session_start();
?>
<html>
<header>
    <title>DungeonXplorer</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                        ['name' => 'Accueil', 'url' => '#'],
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