import Theme from 'vitepress/theme'
import {h, watch} from 'vue'
import './custom.css'

import FooterLogo from './FooterLogo.vue';
import { VuePlausible } from 'vue-plausible'

export default {
  ...Theme,
  Layout() {
    return h(Theme.Layout, null, {
        'aside-bottom': () => h(FooterLogo)
      }
    )
  }
}
