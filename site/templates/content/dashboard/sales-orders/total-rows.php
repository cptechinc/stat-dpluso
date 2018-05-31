<tr>
	<td></td> <td>Subtotal</td> <td colspan="2"></td> <td colspan="2" class="text-right">$ <?= formatmoney($order->subtotal); ?></td> <td colspan="5"></td>
</tr>
<tr>
	<td></td> <td>Tax</td> <td colspan="2"></td> <td colspan="2" class="text-right">$ <?= formatmoney($order->salestax); ?></td> <td colspan="5"></td>
</tr>
<tr>
	<td></td> <td>Freight</td> <td colspan="2"></td> <td colspan="2" class="text-right">$ <?= formatmoney($order->freight); ?></td> <td colspan="5"></td>
</tr>
<tr>
	<td></td> <td>Misc.</td> <td colspan="2"></td><td colspan="2" class="text-right">$ <?= formatmoney($order->misccost); ?></td> <td colspan="5"></td>
</tr>
<tr>
	<td></td>  <td>Total</td> <td colspan="2"></td> <td colspan="2" class="text-right">$ <?= formatmoney($order->ordertotal); ?></td> <td colspan="5"></td>
</tr>
