import {defineConfig} from 'vitepress'

export default defineConfig({
  title: 'Element Exporter',
  description: 'Documentation for Element Exporter, a Craft CMS plugins',
  base: '/craft-exporter',
  lang: 'en-US',
  head: [
    ['meta', {content: 'https://github.com/studioespresso', property: 'og:see_also',}],
  ],
  themeConfig: {
    socialLinks: [
      {icon: 'github', link: 'https://github.com/studioespresso'},
    ],
    logo: '/img/plugin-logo.svg',
    editLink: {
      pattern: 'https://github.com/studioespresso/craft-exporter/edit/develop/docs/docs/:path',
      text: 'Edit this page on GitHub'
    },
    algolia: {
      appId: 'DL97VGR65Z',
      apiKey: '5c7d01633b6cb1c60eb8fa365a5ab351',
      indexName: 'prod-docs-exporter'
    },
    lastUpdatedText: 'Last Updated',
    sidebar: [
      {
        text: 'Topics',
        items: [
          {text: 'Element Exporter', link: '/'},
        ],
      }
    ],
    nav: [
      {text: 'Store', link: 'https://plugins.craftcms.com/element-exporter'},
      {text: 'Issues', link: 'https://github.com/studioespresso/craft-exporter/issues'},
    ],
  },
});
