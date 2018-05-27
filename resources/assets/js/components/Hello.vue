<template>
    <div>
        <h1>Hello, {{msg}}!</h1>
        <el-button @click="visible = true">按钮</el-button>
        <el-dialog :visible.sync="visible">
            <p>欢迎使用 Element</p>
        </el-dialog>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                visible: false,
                msg: 'Fishsocks'
            }
        }
    }
</script>

<style>
    .hello {
        font-size: 2em;
        color: green;
    }
</style>