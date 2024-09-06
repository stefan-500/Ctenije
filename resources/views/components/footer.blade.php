<footer class="bg-gray-800 text-white py-12 px-6">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8 justify-center">
            <div class="logo-col">
                <a href="#" class="text-2xl font-bold mr-8 tracking-wider">Чтеније</a>
                <div class="flex space-x-4 mb-4">
                    <a href="#" class="link">
                        <i class="fa-brands fa-facebook"></i>
                    </a>
                    <a href="#" class="link">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="#" class="link">
                        <i class="fa-brands fa-facebook"></i>
                    </a>
                </div>
                <p class="text-sm">
                    Copyright &copy; 2024 Чтеније,
                    sva prava zadržana.
                </p>
            </div>
            <div>
                <h6 class="footer-naslov text-lg font-semibold mb-4">
                    Kontakt
                </h6>
                <address class="text-sm">
                    <p class="mb-2">
                        Vuka Karadžića 56,<br>
                        Srbija, 11000 Beograd
                    </p>
                    <p class="mb-2">
                        123-456/789
                    </p>
                    <p>
                        prodavnicaknjiga@ctenije.com
                    </p>
                </address>
            </div>
            <div class="mt-5 sm:mt-0">
                <h6 class="footer-naslov text-lg font-semibold mb-4">
                    Nalog
                </h6>
                @auth
                    <p class="mb-2">
                        <a href="{{ route('profile.edit') }}"
                            class="link link-footer text-sm hover:text-gray-400">{{ Auth::user()->ime }}</a>
                    </p>
                @endauth
                @guest
                    <p class="mb-2">
                        <a href="../prijavaReg/registracija.php"
                            class="link link-footer text-sm hover:text-gray-400">Registracija</a>
                    </p>
                    <p class="mb-2">
                        <a href="../prijavaReg/prijava.php" class="link link-footer text-sm hover:text-gray-400">Prijava</a>
                    </p>
                @endguest
                <p class="mb-2">
                    <a href="#" class="link link-footer text-sm hover:text-gray-400">Android aplikacija</a>
                </p>
                <p>
                    <a href="#" class="link link-footer text-sm hover:text-gray-400">IOS aplikacija</a>
                </p>
            </div>
            <div class="mt-5 sm:mt-0">
                <h6 class="footer-naslov text-lg font-semibold mb-4">
                    Preduzeće
                </h6>
                <p class="mb-2">
                    <a href="#" class="link link-footer text-sm hover:text-gray-400">O nama</a>
                </p>
                <p class="mb-2">
                    <a href="#" class="link link-footer text-sm hover:text-gray-400">Pravila korišćenja</a>
                </p>
                <p>
                    <a href="#" class="link link-footer text-sm hover:text-gray-400">Prijavite se za vijesti</a>
                </p>
            </div>
            <div class="mt-5 sm:mt-0">
                <h6 class="footer-naslov text-lg font-semibold mb-4">
                    Resursi
                </h6>
                <p class="mb-2">
                    <a href="#" class="link link-footer text-sm hover:text-gray-400">Dostava</a>
                </p>
                <p class="mb-2">
                    <a href="#" class="link link-footer text-sm hover:text-gray-400">Preduzeća saradnici</a>
                </p>
                <p>
                    <a href="#" class="link link-footer text-sm hover:text-gray-400">Najčešća pitanja</a>
                </p>
            </div>
        </div>
    </div>
</footer>
