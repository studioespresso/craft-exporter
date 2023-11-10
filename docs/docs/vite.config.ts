import {defineConfig} from 'vite'
import VitePressConfig from './.vitepress/config'

const docsSiteBaseUrl = 'https://studioespresso.github.io'
const docsBaseUrl = new URL(VitePressConfig.base!, docsSiteBaseUrl).href.replace(/\/$/, '') + '/';

// https://vitejs.dev/config/
export default defineConfig({
  lang: 'en-US',
  head: [
    [
      'script',
      {
        defer: '',
        async: '',
        src: 'https://stats.studioespresso.co/js/script.js',
        'data-domain': 'studioespresso.github.io/craft-exporter'
      }
    ]
  ],
  plugins: [],
  server: {
    host: '0.0.0.0',
    port: parseInt(process.env.DOCS_DEV_PORT ?? '4000'),
    strictPort: true,
  }
});
