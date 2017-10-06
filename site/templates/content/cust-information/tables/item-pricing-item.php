<table class="table table-striped table-condensed table-excel">
	<tr> <td><b>Item ID</b></td> <td><?= $pricejson['itemid']; ?></td> <td colspan="2"><?= $pricejson['desc1']; ?></td> </tr>
	<tr> <td></td> <td></td> <td colspan="2"><?= $pricejson['desc2']; ?></td> </tr>
	<tr>
		<td><b>Customer ID</b></td>
		<td colspan="2">
			<?= $pricejson['custid']." - ".$pricejson['cust name']; ?> <button type="button" class="btn btn-primary btn-sm" data-dismiss="modal" onclick="iicust('ii-pricing')">Change Customer</button>
		</td>
	</tr>
	<tr>
		<td><b>Cust Price Code</b></td> <td colspan="2"><?= $pricejson['cust price code']." - ".$pricejson['cust price desc']; ?></td> </tr>
	</tr>
</table>
