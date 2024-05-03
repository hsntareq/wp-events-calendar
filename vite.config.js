import { defineConfig } from "vite";
// vite.config.js
export default defineConfig({
	build: {
		// generate .vite/manifest.json in outDir
		manifest: true,
		assetsDir: 'dist',
		outDir: 'public',
		rollupOptions: {
			// overwrite default .html entry
			input: [
				'/assets/js/main.js',
				'/assets/scss/index.scss',
			],
			// output: {
			// 	// overwrite default .html entry
			// 	entryFileNames: '[name].js',
			// 	assetFileNames: '[name][extname]',
			// 	chunkFileNames: '[name].js',
			// },
		},
	},

})
