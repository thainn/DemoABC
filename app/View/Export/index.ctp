<form method="post">
<div class="posts index">
    <h1>Xuất danh sách các Recruit</h1>

    <table border="0" width="100%">
        <tr>
            <td>Lọc  </td> <td></td>
        </tr>
        <tr>
            <td>Người đăng  </td><td><?php echo $this->form->input('User',array('type'=>'select','options'=>$states)); ?></td>
         </tr>
        <tr>
            <td> Thời gian  </td><td>Từ <input style="width:120px" name="from_date" value="2012-10-1" type="text"/> đến <input value="2012-12-1"  style="width:120px" name="to_date" type="text"/></td>
        </tr>
    </table>
    <input type="submit" name="exportCSV" value="Export CSV"/>
    <input type="submit" name="exportPDF" value="Export PDF"/>
</div>
</form>