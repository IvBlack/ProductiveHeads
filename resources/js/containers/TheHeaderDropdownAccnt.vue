<template>
  <CDropdown
      inNav
      class="c-header-nav-items"
      placement="bottom-end"
      add-menu-classes="pt-0"
  >
    <template #toggler>
      <CHeaderNavLink>
        <div class="c-avatar">
          <CIcon name="cil-user"
                 class="c-avatar-img "/>
        </div>
      </CHeaderNavLink>
    </template>
    <CDropdownItem @click="logout()">
      <CIcon name="cil-lock-locked"/>
      Выход
    </CDropdownItem>
  </CDropdown>
</template>

<script>
import api from '@/api'

export default {
  name: 'TheHeaderDropdownAccnt',
  data() {
    return {
      itemsCount: 42,
    }
  },
  methods: {
    logout() {
      let self = this;
        api.auth.logout()
          .then(function () {
            localStorage.setItem('roles', '');
            self.$router.push({path: '/login'});
          }).catch(function (error) {
        console.log(error);
      });
    }
  }
}
</script>
<style scoped>
.c-icon {
  margin-right: 0.3rem;
}
</style>
