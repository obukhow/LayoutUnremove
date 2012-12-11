#LayoutUnremove Magento Module

Undo removes from layout.

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

So you should create a layout for your module and add '<unremove>' tag to handle.

```xml
    <checkout_multishipping translate="label">
        <unremove name="left" />
    </checkout_multishipping>
```