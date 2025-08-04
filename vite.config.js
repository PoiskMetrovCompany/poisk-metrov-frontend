import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    root: '.',
    build: {
        outDir: './public/build',
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/swiper.override.css',
                'resources/css/card.override.css',
                'resources/css/animation.override.css',
                'resources/css/admin/style.css',
                'resources/css/admin/components/dropdown.css',
                'resources/css/candidatesProfiles/index.css',

                'resources/scss/styles.scss',
                'resources/scss/pdf-styles.scss',
                'resources/js/app.js',
                'resources/js/pusher.js',
                'resources/js/infrastructure/loader.js',
                'resources/js/realEstate/showMore.js',
                'resources/js/realEstate/filters.js',
                'resources/js/realEstate/apartmentCardDropdowns.js',
                'resources/js/gallery/aboutLoader.js',
                'resources/js/gallery/galleryLoader.js',
                'resources/js/gallery/reviewsLoader.js',
                'resources/js/gallery/plansLoader.js',
                'resources/js/gallery/homePageCardsGallery.js',
                'resources/js/gallery/buildingProgress.js',
                'resources/js/gallery/renovationGallery.js',
                'resources/js/nonScrollPage/loadNonScrollPage.js',
                'resources/js/nonScrollPage/makeFooterShorter.js',
                'resources/js/mortgage/mortgageCalculatorLoader.js',
                'resources/js/simpleRange.min.js',
                'resources/js/catalogueFilters/filters.js',
                'resources/js/offices/loader.js',
                'resources/js/realEstate/chart.js',
                'resources/js/pdfExport/loadPresentationMap.js',
                'resources/js/realEstate/downloadPresentation.js',
                'resources/js/homePage/mortgageTimer.js',
                'resources/js/homePage/howWeWork.js',
                'resources/js/homePage/apartmentSuggestions.js',
                'resources/js/homePage/homePageFilters.js',
                'resources/js/gallery/newsGallery.js',
                'resources/js/chat/chatLoader.js',
                'resources/js/news/newsLoader.js',
                'resources/js/quiz/loader.js',
                'resources/js/agent/client-register.js',
                'resources/js/agent/client-list.js',
                'resources/js/agent/home-page.js',
                'resources/js/agent/residential-complex-search.js',
                'resources/js/agent/compilations.js',
                'resources/js/profile/profile.js',
                'resources/js/favorites/favorites.js',
                'resources/js/realEstate/realEstate.js',
                'resources/js/plan/plan.js',
                'resources/js/aboutUs/aboutUs.js',
                'resources/js/reservation/index.js',
                'resources/js/reservation/request.js',
                'resources/js/coordinateStorage.js',
                'resources/js/anchorLink.js',


            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    server: {
        watch: {
            ignored: [
                '**/vendor/**',
                '**/node_modules/**',
                '**/.git/**',
            ],
        },
    },
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
});
