<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <title>Document</title>
</head>

<body>
    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                Register as a new user
            </h2>
        </div>
        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <form class="space-y-6" action="YOUR_ACTION_URL" method="POST">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            Full Name
                        </label>
                        <div class="mt-1">
                            <input type="text" name="name" autocomplete="name" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            Email address
                        </label>
                        <div class="mt-1">
                            <input type="email" name="email" autocomplete="email" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Password
                        </label>
                        <div class="mt-1 relative">
                            <input type="<?php echo $visible ? 'text' : 'password'; ?>" name="password" autocomplete="current-password" required class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                        </div>
                    </div>

                    <div>
                        <label for="avatar" class="block text-sm font-medium text-gray-700"></label>
                        <div class="mt-2 flex items-center">
                            <span class="inline-block h-8 w-8 rounded-full overflow-hidden">
                                <?php if ($avatar) : ?>
                                    <img src="<?php echo $avatar; ?>" alt="avatar" class="h-full w-full object-cover rounded-full" />
                                <?php else : ?>
                                    <RxAvatar class="h-8 w-8" />
                                <?php endif; ?>
                            </span>
                            <label for="file-input" class="ml-5 flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                <span>Upload a file</span>
                                <input type="file" name="avatar" id="file-input" accept=".jpg,.jpeg,.png" class="sr-only">
                            </label>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="group relative w-full h-[40px] flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Submit
                        </button>
                    </div>
                    <div class="flex w-full">
                        <h4>Already have an account?</h4>
                        <a href="/login.php" class="text-blue-600 pl-2">Sign In</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>

</html>