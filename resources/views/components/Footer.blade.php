<footer class="bg-white border-t border-gray-100 mt-auto shadow-inner">
    <div class="max-w-7xl mx-auto py-16 px-4 sm:px-6 lg:px-8">
        <div class="xl:grid xl:grid-cols-3 xl:gap-12">
          <img
    src="{{ asset('.png') }}"
    alt="Oasis"
    class="h-12 w-auto"
/>
            <div class="space-y-6 xl:col-span-1">
          
                <p class="text-gray-500 text-sm leading-relaxed max-w-sm">
                    Menghadirkan akomodasi premium berstandar internasional dengan kehangatan pelayanan bintang lima demi kenyamanan istirahat Anda.
                </p>
                <div class="flex space-x-5 text-gray-400">
                    <a href="#" class="hover:text-blue-600 transition-colors duration-200">
                        <span class="sr-only">Facebook</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
                    </a>
                    <a href="#" class="hover:text-pink-600 transition-colors duration-200">
                        <span class="sr-only">Instagram</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.01 3.81.058 1.026.048 1.724.21 2.335.446a4.59 4.59 0 011.64 1.066 4.59 4.59 0 011.065 1.64c.237.61.399 1.308.447 2.335.047 1.026.057 1.379.057 3.81 0 2.43-.01 2.784-.057 3.81-.048 1.026-.21 1.724-.447 2.335a4.59 4.59 0 01-1.065 1.64 4.59 4.59 0 01-1.64 1.066c-.61.236-1.308.399-2.335.446-1.026.048-1.379.057-3.81.057-2.43 0-2.784-.01-3.81-.057-1.026-.048-1.724-.21-2.335-.446a4.59 4.59 0 01-1.64-1.066 4.59 4.59 0 01-1.066-1.64c-.236-.61-.399-1.308-.446-2.335C2.01 15.099 2 14.746 2 12.315c0-2.43.01-2.784.058-3.81.048-1.026.21-1.724.446-2.335a4.59 4.59 0 011.066-1.64 4.59 4.59 0 011.64-1.066c.61-.236 1.308-.399 2.335-.446 1.026-.048 1.379-.057 3.81-.057zM12 6.865A5.135 5.135 0 1017.135 12 5.135 5.135 0 0012 6.865zm0 8.414A3.28 3.28 0 1115.28 12 3.28 3.28 0 0112 15.28zm5.122-8.627a1.127 1.127 0 101.127 1.127 1.127 1.127 0 00-1.127-1.127z" clip-rule="evenodd"/></svg>
                    </a>
                    <a href="#" class="hover:text-blue-400 transition-colors duration-200">
                        <span class="sr-only">Twitter</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                    </a>
                </div>
            </div>
            
            <div class="mt-12 grid grid-cols-2 gap-8 xl:mt-0 xl:col-span-2">
                <div class="md:grid md:grid-cols-2 md:gap-8">
                    <div>
                        <h3 class="text-xs font-bold text-gray-400 tracking-widest uppercase">Jelajah</h3>
                        <ul class="mt-4 space-y-3">
                            <li>
                                <a href="{{ route('rooms') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-blue-600 transform hover:translate-x-1 transition-all duration-200">
                                    Rooms
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('facilities') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-blue-600 transform hover:translate-x-1 transition-all duration-200">
                                    Facilities
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('restaurant') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-blue-600 transform hover:translate-x-1 transition-all duration-200">
                                    Restaurant
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="mt-12 md:mt-0">
                        <h3 class="text-xs font-bold text-gray-400 tracking-widest uppercase">Dukungan</h3>
                        <ul class="mt-4 space-y-3">
                            <li>
                                <a href="{{ route('contact') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-blue-600 transform hover:translate-x-1 transition-all duration-200">
                                    Contact
                                </a>
                            </li>
                            <li>
                                <a href="#" class="inline-flex items-center text-sm text-gray-500 hover:text-blue-600 transform hover:translate-x-1 transition-all duration-200">
                                    Pusat Bantuan
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-12 border-t border-gray-100 pt-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-xs text-gray-400 order-2 md:order-1">
                &copy; {{ date('Y') }} Hotel App. All rights reserved.
            </p>
            <div class="flex space-x-6 text-xs text-gray-400 order-1 md:order-2">
                <a href="#" class="hover:text-gray-600 transition-colors">Kebijakan Privasi</a>
                <a href="#" class="hover:text-gray-600 transition-colors">Syarat & Ketentuan</a>
            </div>
        </div>
    </div>
</footer>