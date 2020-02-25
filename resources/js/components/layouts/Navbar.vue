<template>
  <div class="navWrapper">
    <div id="menu" :class="{ active: isActive }">
      <el-menu mode="horizontal" @select="handleSelect" :router="true">
        <el-menu-item id="logo" index="/" :route="{ name: 'Root'}">
          <logo></logo>
        </el-menu-item>
        <el-menu-item index="/hello" :route="{ name: 'Hello' }">Hello</el-menu-item>
        <el-menu-item index="/projects" :route="{ name: 'Projects' }">Projects</el-menu-item>
        <el-menu-item index="/about" :route="{ name: 'About' }">About</el-menu-item>
        <el-menu-item index="/contact" :route="{ name: 'Contact' }">Contact</el-menu-item>
      </el-menu>
    </div>
    <div id="toggle" @click="select()">
      <div class="span" id="top" :class="{ active: isActive }"></div>
      <div class="span" id="middle" :class="{ active: isActive }"></div>
      <div class="span" id="bottom" :class="{ active: isActive }"></div>
    </div>
  </div>
</template>

<script>
import Logo from "../Logo";
export default {
  components: {
    Logo
  },
  data() {
    return {
      activeIndex: "/hello",
      isActive: false
    };
  },
  methods: {
    handleSelect(key, keyPath) {
      console.log(key, keyPath);
    },
    select: function() {
      this.isActive = !this.isActive;
    }
  }
};
</script>
<style lang="scss" scoped>
$nav-toggle-color: #0e5fc2;
$primary-color: #21cf5f;

/* BreakPoints */

// Mobile devices
$screen-xs-min: 360px;

// Small tablets and large smartphones (landscape view)
$screen-sm-min: 576px;

// Small tablets (portrait view)
$screen-md-min: 768px;

// Tablets and small desktops
$screen-lg-min: 992px;

// Large tablets and desktops
$screen-xl-min: 1200px;
#app {
  .logo-header {
    padding-left: 15px;
    padding-right: 50px;
    z-index: 100;
  }

  .navWrapper {
    width: 100%;
    position: fixed;
    top: 0;
  }

  #logo.el-menu-item.is-active {
    border-bottom-color: transparent;
  }

  #toggle {
    position: absolute;
    right: 20px;
    top: 14px;
    z-index: 999;
    width: 40px;
    height: 40px;
    cursor: pointer;
    float: right;
    transition: all 0.3s ease-out;
    visibility: hidden;
    opacity: 0;
  }

  #toggle .span {
    border-radius: 10px;
    background: $nav-toggle-color;
    transition: all 0.3s ease-out;
    backface-visibility: hidden;
  }

  #top.span.active {
    transform: rotate(45deg) translateX(3px) translateY(5px);
  }

  #middle.span.active {
    opacity: 0;
  }

  #bottom.span.active {
    transform: rotate(-45deg) translateX(8px) translateY(-10px);
  }

  @media only screen and (max-width: $screen-md-min) {
    .logo-header {
      padding: 0;
    }

    #toggle {
      visibility: visible;
      opacity: 1;
      margin-top: 6px;
    }

    #toggle .span {
      height: 4px;
      margin: 5px 0;
      transition: all 0.3s ease-out;
      backface-visibility: visible;
      visibility: visible;
      opacity: 1;
    }

    #menu .el-menu-item {
      display: none;
    }

    #menu.active {
      margin: 70px 0;
      visibility: visible;
      opacity: 0.98;
      transition: all 0.5s ease-out;

      // @include sm-menu;
    }
  }
}
</style>