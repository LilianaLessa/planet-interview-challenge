<div id="Cart">
  <strong>Cart ({$items|count} items):</strong>
  <ul>
    {assign var="counter" value=0}
    {foreach from=$items item=item}
      <li>item {$counter}: {$item->display()}</li>
      {assign var="counter" value=($counter+1)}
    {/foreach}
  </ul>
</div>
