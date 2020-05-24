

/*
    <form id="thrown" method="get" action="test.php">
        <input type="hidden" name="recv" value="pasm-router">
        <input type="hidden" name="from" value="localhost">
        <input type="hidden" name="target" value="final.php">
        <input type="hidden" name="port" value="80">
        <input type="hidden" name="user" value=".">
        <input type="hidden" name="req" value="adduser">
        <input type="hidden" name="sub" value=".">
        <button onload="submit()" onclick="submit">HEY!</button>
    </form>
*/


<script> document.getElementById("thrown").submit();</script>