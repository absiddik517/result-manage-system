<template>
    <footer class="main-footer">
        <strong>Copyright &copy; 2022-{{ currentDate.getFullYear() }} <a href="https://www.facebook.com/absiddik.u">A.B. Siddik</a>.</strong>
        All rights reserved.
        <div class="float-right d-none d-sm-inline-block">
            <b>Version</b> 1.1.10
        </div>
    </footer>
</template>


<script>
export default {
    data() {
        return {
            currentDate: new Date,
        }
    }
}
</script>
