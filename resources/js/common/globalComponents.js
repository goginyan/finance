import Vue from 'vue'
import VxTooltip from '@js/common/layouts/components/vx-tooltip/VxTooltip.vue'
import VxCard  from '@js/common/components/vx-card/VxCard.vue'
import VxList  from '@js/common/components/vx-list/VxList.vue'
import VxBreadcrumb  from '@js/common/layouts/components/VxBreadcrumb.vue'
import FeatherIcon  from '@js/common/components/FeatherIcon.vue'
import VxInputGroup  from '@js/common/components/vx-input-group/VxInputGroup.vue'

Vue.component(VxTooltip.name, VxTooltip)
Vue.component(VxCard.name, VxCard)
Vue.component(VxList.name, VxList)
Vue.component(VxBreadcrumb.name, VxBreadcrumb)
Vue.component(FeatherIcon.name, FeatherIcon)
Vue.component(VxInputGroup.name, VxInputGroup)


// v-select component
import vSelect from 'vue-select'

// Set the components prop default to return our fresh components
vSelect.props.components.default = () => ({
  Deselect: {
    render: createElement => createElement('feather-icon', {
      props: {
        icon: 'XIcon',
        svgClasses: 'w-4 h-4 mt-1'
      }
    }),
  },
  OpenIndicator: {
    render: createElement => createElement('feather-icon', {
      props: {
        icon: 'ChevronDownIcon',
        svgClasses: 'w-5 h-5'
      }
    }),
  },
});

Vue.component(vSelect)
