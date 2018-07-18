<tr class="first-total-row">
	<td colspan="2"></td> <td colspan="2">Subtotal</td> <td colspan="2" class="text-right">$ <?= $page->stringerbell->format_money($order->subtotal); ?></td> <td colspan="5"></td>
</tr>
<tr>
	<td colspan="2"></td> <td colspan="2">Tax</td>  <td colspan="2" class="text-right">$ <?= $page->stringerbell->format_money($order->salestax); ?></td><td colspan="5"></td>
</tr>
<tr>
	<td colspan="2"></td> <td colspan="2">Freight</td>  <td colspan="2" class="text-right">$ <?= $page->stringerbell->format_money($order->freight); ?></td> <td colspan="5"></td>
</tr>
<tr>
	<td colspan="2"></td> <td colspan="2">Misc.</td> <td colspan="2" class="text-right">$ <?= $page->stringerbell->format_money($order->misccost); ?></td> <td colspan="5"></td>
</tr>
<tr>
	<td colspan="2"></td> <td colspan="2">Total</td> <td colspan="2" class="text-right">$ <?= $page->stringerbell->format_money($order->ordertotal); ?></td> <td colspan="5s"></td>
</tr>
