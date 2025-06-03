import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
  plugins: [vue()],
  resolve: {
    alias: {
      "@common": "/src/components/common",
    },
  },
  server: {
    host: '0.0.0.0',         // Permite acesso externo (ngrok)
    port: 5174,
    strictPort: true,
    cors: true,              // Habilita CORS
    origin: '*',             // Ignora origem
    allowedHosts: 'all',     // Libera todos os domínios
    historyApiFallback: true
  },
});
