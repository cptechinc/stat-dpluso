<tr>
	<td colspan="2"></td> <td></td> <td>Subtotal</td> <td colspan="4"></td> <td colspan="2" class="text-right">$ <?= $page->stringerbell->format_money($order->subtotal); ?></td> <td></td>
</tr>
<tr>
	<td colspan="2"></td> <td></td> <td>Tax</td> <td colspan="4"></td> <td colspan="2" class="text-right">$ <?= $page->stringerbell->format_money($order->salestax); ?></td> <td></td>
</tr>
<tr>
	<td colspan="2"></td> <td></td> <td>Freight</td> <td colspan="4"></td> <td colspan="2" class="text-right">$ <?= $page->stringerbell->format_money($order->freight); ?></td> <td></td>
</tr>
<tr>
	<td colspan="2"></td> <td></td> <td>Misc.</td> <td colspan="4"></td><td colspan="2" class="text-right">$ <?= $page->stringerbell->format_money($order->misccost); ?></td> <td></td>
</tr>
<tr>
	<td colspan="2"></td> <td></td>  <td>Total</td> <td colspan="4"></td> <td colspan="2" class="text-right">$ <?= $page->stringerbell->format_money($order->ordertotal); ?></td> <td></td>
</tr>
