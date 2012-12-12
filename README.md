#LayoutUnremove Magento Module

Undo removes and action method calls from layout.

## Undo removes
This Magento module allows you to easily undo removes from layout.

Here is the checkout.xml layout from base_default package.

```xml
    <checkout_multishipping translate="label">
        <!-- Mage_Checkout -->
        <remove name="right"/>
        <remove name="left"/>
    </checkout_multishipping>
```
And you need to return left column back in your module.

So you should create a layout for your module and add 'unremove' tag to the handle.

```xml
    <checkout_multishipping translate="label">
        <unremove name="left" />
    </checkout_multishipping>
```

## Undo action methods calls

Action method calls can be undone easily too. For instance, Magento has the following code in bundle.xml file

```xml
<action method="addPriceBlockType"><type>bundle</type><block>bundle/catalog_product_price</block><template>bundle/catalog/product/price.phtml</template></action>
```
And there are no such methods as `removePriceBlockType`, so we wan't to remove this action. We can do it!

```xml
<unaction method="addPriceBlockType" />
```