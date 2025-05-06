
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import glob from 'glob';
import path from 'path';

/**
 * ビルド対象のファイルを一括で取得する
 * @param {*} pattern 
 * @param {*} ignoreList 
 * @returns 
 */
function getFiles(pattern, ignoreList = []) {
    return glob.sync(pattern, { ignore: ignoreList }).map(file => file.replace(/\\/g, '/'));
}

// ビルド対象外のjsを管理
const ignoreJsList = [
    'resources/js/vendor/**',
    'resources/css/_template/**',
];
// ビルド対象外のjsを管理
const ignoreCssList = [
    'resources/js/vendor/**',
    'resources/css/_template/**',
];
// ... を使った配列のアンパック
export default defineConfig({
    server: {
        host: true,
        // host: 'localhost',
        // host: '0.0.0.0',
        strictPort: true
      },
    plugins: [
        laravel({
            input: [
                ...getFiles('resources/js/**/*.js', ignoreJsList),
                ...getFiles('resources/css/**/*.css', ignoreCssList),
            ],
            refresh: true,
        }),
        vue(),
    ],
});