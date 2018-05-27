<template>
    <el-container :style="{ 'height': fullHeight + 'px'}">
        <el-aside :width="sideWidth+'px'">
            <el-header class="header-logo">
                <img src="../image/logo.png"/>
            </el-header>
            <el-menu class="el-menu-vertical-demo" @open="handleOpen" @close="handleClose" :collapse="isCollapse"
                     background-color="#545c64"
                     text-color="#fff"
                     active-text-color="#ffd04b">
                <template v-for="(nav, index) in navs">
                    <el-submenu :index="index" v-if="nav.children != null">
                        <template slot="title">
                            <i :class="nav.icon"></i>
                            <span slot="title">{{nav.name}}</span>
                        </template>
                        <el-menu-item v-for="(sub_nav, sub_index) in nav.children" :index="index+'-'+sub_index" :key="sub_index">
                            <i :class="sub_nav.icon"></i>
                            <span slot="title">{{sub_nav.name}}</span>
                        </el-menu-item>
                    </el-submenu>
                    <el-menu-item v-else :index="index">
                        <i :class="nav.icon"></i>
                        <span slot="title">{{nav.name}}</span>
                    </el-menu-item>
                </template>
            </el-menu>
        </el-aside>
        <el-container>
            <el-header>Header</el-header>
            <el-main><router-view></router-view></el-main>
        </el-container>
    </el-container>
</template>

<script>
    import _ from 'lodash'
    export default {
        data() {
            return {
                isCollapse: (document.documentElement.clientWidth < 900) ? true : false,
                fullHeight: document.documentElement.clientHeight,
                sideWidth: (document.documentElement.clientWidth < 900) ? 65 : 201,
                navs : [
                    {
                        name: '首页',
                        icon: 'el-icon-location'
                    },
                    {
                        name: '操作一',
                        icon: 'el-icon-setting'
                    },
                    {
                        name: '操作二',
                        icon: 'el-icon-menu',
                        children: [
                            {
                                name: '分组一',
                                icon: 'el-icon-star-on'
                            },
                            {
                                name: '分组二',
                                icon: 'el-icon-picture'
                            },
                            {
                                name: '分组三',
                                icon: 'el-icon-upload'
                            },
                        ]
                    }
                ]
            };
        },
        methods: {
            handleOpen(key, keyPath) {
                console.log(this.fullHeight);
                console.log(key, keyPath);
            },
            handleClose(key, keyPath) {
                console.log(key, keyPath);
            }
        },
        mounted: function () {
            const that = this
            window.onresize = _.debounce(() => {
                that.fullHeight = document.documentElement.clientHeight
                if( document.documentElement.clientWidth < 900){
                    that.sideWidth = 65;
                    that.isCollapse = true;
                }else{
                    that.sideWidth = 201;
                    that.isCollapse = false;
                }
            }, 400)
        }
    }
</script>
<style>
    body{
        margin: auto;
    }
    .el-header, .el-footer {
        background-color: #545c64;
        color: #fff;
        text-align: center;
        line-height: 60px;
    }

    .header-logo{
        text-align: left;
        background-color: #545c64;
        /*border-bottom: solid 1px #e6e6e6;*/
    }
    .el-menu{
        margin-top: 30px;
    }

    .el-aside {
        background-color: #545c64;
        color: #333;
        text-align: center;
        line-height: 200px;
        border-right: solid 1px #e6e6e6;
    }

    .el-main {
        background-color: #E9EEF3;
        color: #333;
        text-align: center;
        line-height: 160px;
    }

    .el-container:nth-child(5) .el-aside,
    .el-container:nth-child(6) .el-aside {
        line-height: 260px;
    }

    .el-container:nth-child(7) .el-aside {
        line-height: 320px;
    }
    .el-menu-vertical-demo:not(.el-menu--collapse) {
        width: 200px;
        min-height: 400px;
    }
    .el-submenu {
        text-align: left;
    }
    .el-menu-item {
        text-align: left;
    }
</style>