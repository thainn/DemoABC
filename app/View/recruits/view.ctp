<?php
 
 echo $this->element('left');

?>

<div class="posts view">
<table border="0" cellpadding="2" cellspacing="2" width="100%">
    
       <tr>	
     
     </tr>
    
     <tr>
     
        <th>Id</th>
        <th>Title</th>
        <th>Content</th>
     
    </tr>

    <tr>
        <td><?php echo $rercuit['Recruit']['id'];?></td>
        <td><?php echo $rercuit['Recruit']['title'];?></td>
		<td><?php echo $rercuit['Recruit']['content'];?></td>

    </tr>
 
</table>

</div>