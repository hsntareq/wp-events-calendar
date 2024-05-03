import { defineConfig } from "vite";
// vite.config.js
export default defineConfig({
	build: {
		// generate .vite/manifest.json in outDir
		manifest: true,
		assetsDir: './',
		outDir: 'dist',
		rollupOptions: {
			// overwrite default .html entry
			input: {
				'admin': '/assets/src/admin.js',
				'front': '/assets/src/admin.scss'
			},
			output: {
				'admin.js': '/assets/js/admin.min.js',
				'front.js': '/assets/js/front.min.js',

				// overwrite default .html entry
				// entryFileNames: '[name].js',
				// assetFileNames: '[name][extname]',
				// chunkFileNames: '[name].js',
			},
		},
	},

})
