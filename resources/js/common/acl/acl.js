import Vue from 'vue'
import { AclInstaller, AclCreate, AclRule } from 'vue-acl'
import router from '@js/admin/router'

Vue.use(AclInstaller)

export default new AclCreate({
    initial         : 'operator',
    notfound        : '/404',
    router,
    acceptLocalRules: true,
    globalRules     : {
        admin   : new AclRule('admin').generate(),
        operator: new AclRule('operator').generate(),
    }
})
