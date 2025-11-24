<?php
    include_once("../components/header.php");

    $message = $_GET['message'] ?? '';

    if(isset($_SESSION['user_id'])) {
        header('Location: /index.php');
        exit;
    }
?>

<main class="flex-grow flex flex-row items-center justify-between px-20 py-10 bg-[#1a1a1a]">

    <div class="text-white max-w-xl">
        <h1 class="text-5xl font-bold mb-4">DungeonXplorer</h1>
        <p class="text-lg text-gray-300">Content de vous revoir</p>
    </div>

    <div class="w-full max-w-sm bg-white p-8 rounded-xl shadow-lg">

        <?php if ($message): ?>
            <div class="mb-4 text-center text-red-600 font-medium">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form action="../api_php/loginAPI.php" method="GET" class="space-y-5">

            <div>
                <label for="email" class="block mb-1 text-sm font-medium text-gray-700">Email</label>
                <input id="email" type="email" name="email" required
                       class="w-full p-2.5 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-black">
            </div>

            <div>
                <label for="password" class="block mb-1 text-sm font-medium text-gray-700">Password</label>
                <input id="password" type="password" name="password" required
                       class="w-full p-2.5 rounded-md border border-gray-300 focus:outline-none focus:ring-2 focus:ring-black">
            </div>

            <button type="submit"
                    class="w-full border border-gray-400 text-black py-2 rounded-md hover:bg-gray-100 transition">
                Sign In
            </button>
        </form>

        <div class="mt-4 text-left">
            <a href="#" class="text-xs text-gray-600 hover:underline">Forgot password?</a>
        </div>

    </div>
</main>

<?php 
    include_once("../components/footer.php");
?>
