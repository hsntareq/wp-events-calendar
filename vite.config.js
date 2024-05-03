import { defineConfig } from "vite";
// vite.config.js
export default defineConfig({
	server: {
		port: 3030,
	},
	build: {
		// generate .vite/manifest.json in outDir
		manifest: true,
		assetsDir: './',
		outDir: 'assets/dist',
		rollupOptions: {
			input: {
				'admin': '/assets/src/admin.js',
				'front': '/assets/src/front.js'
			},
			output: {
				entryFileNames: '[name].js',
				assetFileNames: '[name][extname]',
				chunkFileNames: '[name].min.[extname]',
			},
			/*
			output: [
				{
					dir: 'assets/dist/js',
					format: 'es',
					entryFileNames: '[name].min.js',
					chunkFileNames: '[name]-[hash].min.js',
					assetFileNames: '[name][extname]',
				},
				{
					dir: 'assets/dist/css',
					format: 'es',
					entryFileNames: '[name].min.css',
					chunkFileNames: '[name]-[hash].min.css',
					assetFileNames: '[name][extname]',
				},
			],
			*/
		},
	},

})
