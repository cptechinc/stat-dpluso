<tr>
	<td></td> <td>Subtotal</td> <td colspan="2"></td> <td colspan="2" class="text-right">$ <?= $page->stringerbell->format_money($order->subtotal); ?></td> <td colspan="5"></td>
</tr>
<tr>
	<td></td> <td>Tax</td> <td colspan="2"></td> <td colspan="2" class="text-right">$ <?= $page->stringerbell->format_money($order->salestax); ?></td> <td colspan="5"></td>
</tr>
<tr>
	<td></td> <td>Freight</td> <td colspan="2"></td> <td colspan="2" class="text-right">$ <?= $page->stringerbell->format_money($order->freight); ?></td> <td colspan="5"></td>
</tr>
<tr>
	<td></td> <td>Misc.</td> <td colspan="2"></td><td colspan="2" class="text-right">$ <?= $page->stringerbell->format_money($order->misccost); ?></td> <td colspan="5"></td>
</tr>
<tr>
	<td></td>  <td>Total</td> <td colspan="2"></td> <td colspan="2" class="text-right">$ <?= $page->stringerbell->format_money($order->ordertotal); ?></td> <td colspan="5"></td>
</tr>
