<?php include_once("../php/components/header.php"); ?>

<main class="min-h-screen flex flex-col items-center justify-center font-sans text-white bg-[#1A1A1A]">

    <div class="max-w-7xl mx-auto mb-12 text-center border-b border-gray-800 pb-8 animate-fade-in-down mt-24">
        <h2 class="text-4xl md:text-5xl font-extrabold uppercase text-[#f2a900] mb-2 drop-shadow-lg">
            Guilde des Aventuriers
        </h2>
        <p class="text-gray-400 italic">Gérez vos héros et préparez votre prochaine quête.</p>
        <?php if (isset($error) && $error): ?>
            <div class="mt-4 p-3 bg-red-900/80 border border-red-500 text-white rounded font-bold inline-block"><?= $error ?></div>
        <?php endif; ?>
    </div>

    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 pb-24 px-4">
        
        <div onclick="toggleModal('modal-create')" class="cursor-pointer group relative h-96 border-2 border-dashed border-gray-700 rounded-xl flex flex-col items-center justify-center hover:border-[#f2a900] hover:bg-gray-900/50 transition-all duration-300">
            <div class="w-20 h-20 rounded-full bg-gray-800 group-hover:bg-[#f2a900] flex items-center justify-center transition-colors duration-300 mb-4 shadow-lg">
                <i class="fa-solid fa-plus text-3xl text-gray-400 group-hover:text-black"></i>
            </div>
            <h3 class="text-xl font-bold text-gray-400 group-hover:text-white uppercase tracking-wider">Créer un Héros</h3>
        </div>

        <?php foreach ($heroes as $hero): ?>
            <?php 
                $cId = $hero['class_id'];
                $cName = $classesById[$cId]['name'] ?? 'Inconnu';
                
                $icon = "fa-question";
                if(str_contains(strtolower($cName), 'guerrier')) $icon = "fa-shield-halved";
                elseif(str_contains(strtolower($cName), 'mage')) $icon = "fa-hat-wizard";
                elseif(str_contains(strtolower($cName), 'voleur')) $icon = "fa-mask";
            ?>
            <div class="group relative bg-gray-900 rounded-xl overflow-hidden border border-gray-700 shadow-lg hover:shadow-[0_0_20px_rgba(242,169,0,0.3)] hover:-translate-y-2 transition-all duration-300">
                <div class="h-48 w-full bg-cover bg-center relative" style="background-image: url('<?= htmlspecialchars($hero['image']) ?>');">
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-transparent to-transparent"></div>
                    <form method="POST" action="" onsubmit="return confirmDelete(event, '<?= htmlspecialchars($hero['name']) ?>');" class="absolute top-2 right-2 z-20"> 
                        <input type="hidden" name="delete_hero_id" value="<?= $hero['id'] ?>">
                        <button type="submit" class="text-gray-400 hover:text-red-500 transition duration-200 p-2 bg-gray-900/50 hover:bg-gray-800 rounded-full"><i class="fa-solid fa-trash"></i></button>
                    </form>
                    <div class="absolute top-2 right-12 bg-[#941515] text-white text-xs font-bold px-2 py-1 rounded border border-red-400 z-10">
                        Niv. <?= $hero['current_level'] ?>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-2xl font-bold text-[#f2a900] mb-1"><?= htmlspecialchars($hero['name']) ?></h3>
                    <p class="text-xs text-gray-500 mb-4 uppercase tracking-widest flex items-center gap-2">
                        <i class="fa-solid <?= $icon ?>"></i> <?= htmlspecialchars($cName) ?>
                    </p>
                    <div class="grid grid-cols-3 gap-2 text-center text-sm text-gray-300 mb-4 bg-gray-800 rounded p-2 border border-gray-700">
                        <div title="PV"><i class="fa-solid fa-heart text-red-600"></i> <?= $hero['pv'] ?></div>
                        <div title="Mana"><i class="fa-solid fa-bolt text-blue-400"></i> <?= $hero['mana'] ?></div>
                        <div title="Force"><i class="fa-solid fa-hand-fist text-orange-500"></i> <?= $hero['strength'] ?></div>
                    </div>
                    <a href="/View/chapitre/index.php?hero=<?= $hero['id'] ?>" class="block w-full py-2 text-center bg-[#f2a900] hover:bg-yellow-500 text-black font-bold rounded uppercase text-sm transition-colors shadow-md">
                        Jouer
                    </a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="modal-create" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="absolute inset-0 bg-black/90 backdrop-blur-sm transition-opacity" onclick="toggleModal('modal-create')"></div>
        <div class="relative min-h-screen md:min-h-0 md:absolute md:top-5 md:left-1/2 md:transform md:-translate-x-1/2 w-full max-w-4xl bg-[#1a1a1a] border-2 border-[#941515] rounded-none md:rounded-lg shadow-[0_0_50px_rgba(148,21,21,0.5)] p-6 md:p-8">
            <div class="flex justify-between items-center mb-6 border-b border-gray-700 pb-4">
                <h3 class="text-2xl font-bold text-white"><span class="text-[#f2a900]">Nouvelle</span> Aventure</h3>
                <button onclick="toggleModal('modal-create')" class="text-gray-400 hover:text-white transition"><i class="fa-solid fa-xmark text-2xl"></i></button>
            </div>
            <form action="" method="POST" class="space-y-6">
                <input type="hidden" name="create_hero" value="1">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-[#f2a900] font-semibold mb-2 text-sm uppercase">Nom</label>
                            <input type="text" name="characterName" required class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded text-white focus:border-[#f2a900] transition">
                        </div>
                        <div>
                            <label class="block text-[#f2a900] font-semibold mb-2 text-sm uppercase">Classe</label>
                            <select id="classSelect" name="class" required class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded text-white focus:border-[#f2a900] transition cursor-pointer">
                                <?php foreach ($classes as $c): ?>
                                    <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?> (<?= $c['base_pv'] ?> PV)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="flex justify-center py-4 bg-gray-900/50 rounded border border-gray-700">
                            <img id="classImagePreview" src="" alt="Aperçu" class="h-32 w-auto pixel-art rendering-pixelated">
                        </div>
                        <div>
                            <label class="block text-[#f2a900] font-semibold mb-2 text-sm uppercase">Histoire</label>
                            <textarea name="descChar" rows="3" required class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded text-white focus:border-[#f2a900] transition resize-none"></textarea>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div id="equipmentInfo" class="bg-gray-900 border border-gray-600 rounded-lg p-4">
                            <h3 class="text-[#f2a900] font-semibold mb-3 text-sm uppercase">🎒 Équipement de départ</h3>
                            <div id="equipmentDetails" class="text-gray-300 text-sm space-y-2"></div>
                        </div>
                        
                        <div>
                            <label class="block text-[#f2a900] font-semibold mb-2 text-sm uppercase">⚡ Initiative</label>
                            <input type="number" name="initiative" id="initiativeInput" value="0" min="0" max="10" required class="w-full px-4 py-3 bg-gray-900 border border-gray-600 rounded text-white focus:border-[#f2a900] transition">
                            <p class="text-gray-500 text-xs mt-1" id="initiativeHelp">Entre 0 et 10</p>
                        </div>
                    </div>
                </div>
                <div class="pt-4 border-t border-gray-700">
                    <button type="submit" class="w-full py-4 bg-gradient-to-r from-[#941515] to-red-900 hover:from-red-600 hover:to-red-800 text-white font-bold uppercase tracking-widest rounded shadow-lg transform hover:scale-[1.01] transition-all">Incarner ce Héros</button>
                </div>
            </form>
        </div>
    </div>
</main>

<script>
    
    const loadouts = <?= json_encode($defaultLoadouts) ?>;
    const items = <?= json_encode($items) ?>;
    
    // Config pour initiative
    const classConfigs = {
        1: { minInit: 0, maxInit: 10, help: 'Standard 0-10' }, // Guerrier
        2: { minInit: 0, maxInit: 10, help: 'Standard 0-10' }, // Mage
        3: { minInit: 5, maxInit: 15, help: 'Voleur 5-15' }    // Voleur
    };

    function updateFormUI() {
        const select = document.getElementById('classSelect');
        const selectedId = select.value;
        const config = loadouts[selectedId];
        const uiConfig = classConfigs[selectedId] || classConfigs[1];

        if(config) document.getElementById('classImagePreview').src = config.img;

        const detailsDiv = document.getElementById('equipmentDetails');
        let html = '';
        if (config) {
            if (config.weapon_id && items[config.weapon_id]) html += `<p class="flex items-center"><span class="mr-2 text-green-500">✔</span> ${items[config.weapon_id].name}</p>`;
            if (config.armor_id && items[config.armor_id]) html += `<p class="flex items-center"><span class="mr-2 text-green-500">✔</span> ${items[config.armor_id].name}</p>`;
            if (config.shield_id && items[config.shield_id]) html += `<p class="flex items-center"><span class="mr-2 text-green-500">✔</span> ${items[config.shield_id].name}</p>`;
        }
        detailsDiv.innerHTML = html;

        const initInput = document.getElementById('initiativeInput');
        initInput.min = uiConfig.minInit;
        initInput.max = uiConfig.maxInit;
        initInput.value = uiConfig.minInit;
        document.getElementById('initiativeHelp').textContent = uiConfig.help;
    }

    document.getElementById('classSelect').addEventListener('change', updateFormUI);
    window.onload = updateFormUI;
    function toggleModal(id) { document.getElementById(id).classList.toggle("hidden"); }
    function confirmDelete(e, n) { e.preventDefault(); if(confirm(`Supprimer ${n} ?`)) e.target.submit(); }
</script>

<style>.rendering-pixelated { image-rendering: pixelated; }</style>
<?php include_once("../php/components/footer.php"); ?>