import { defineConfig } from "vite";
import vue from "@vitejs/plugin-vue";

// https://vite.dev/config/
export default defineConfig({
  resolve: {
    alias: {
      "@common": "/src/components/common",
    },
  },
  plugins: [vue()],
  server: {
    historyApiFallback: true,
  },
});
