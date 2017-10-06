<?php
    $custpricejson = json_decode(file_get_contents($config->companyfiles."json/custpricecodetbl.json"), true);
    $custpricecodes = array_keys($custpricejson['data']);

    $salespersonjson = json_decode(file_get_contents($config->companyfiles."json/salespersontbl.json"), true);
    $salespersoncodes = array_keys($salespersonjson['data']);
?>
<form action="<?= $config->pages->customer.'redir/'; ?>" method="post">
    <input type="hidden" name="action" value="add-customer">
    <div class="row">
        <div class="col-xs-6">
           <legend>Bill-To</legend>
            <table class="table table-striped table-bordered table-condensed">
                <tbody>
                    <tr>
                        <td class="control-label">Customer</td>
                        <td><p class="form-control-static">NEW CUSTOMER</p></td>
                    </tr>
                    <tr>
                        <td class="control-label">Name</td>
                        <td><input type="text" class="form-control input-sm required" name="billto-name" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">Address</td>
                        <td><input type="text" class="form-control input-sm required" name="billto-address" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">Address 2</td>
                        <td><input type="text" class="form-control input-sm" name="billto-address2" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">Address 3</td>
                        <td><input type="text" class="form-control input-sm" name="billto-address3" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">City</td>
                        <td><input type="text" class="form-control input-sm required" name="billto-city" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">State</td>
                        <td><input type="text" class="form-control input-sm required" name="billto-state" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">Zip</td>
                        <td><input type="text" class="form-control input-sm required" name="billto-zip" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">Country</td>
                        <td>
                            <?php $countries = getcountries(); ?>
                            <select name="billto-country" class="form-control input-sm">
                                <option value="USA">United States</option>
                                <?php foreach ($countries as $country) : ?>
                                    <option value="<?= $country['ccode']; ?>"><?= $country['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="control-label">Phone</td>
                        <td><input type="tel" class="form-control input-sm phone-input" name="billto-phone" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">Ext.</td>
                        <td><input type="tel" class="form-control input-sm qty pull-right" name="billto-ext" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">Fax</td>
                        <td><input type="tel" class="form-control input-sm phone-input" name="billto-fax" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">E-mail</td>
                        <td><input type="email" class="form-control input-sm" name="billto-email" value=""></td>
                    </tr>
                </tbody>
           </table>
        </div> <!-- end bill to column -->

        <div class="col-xs-6">
           <legend>Ship-To</legend>
            <table class="table table-striped table-bordered table-condensed">
                <tbody>
                    <tr>
                        <td class="control-label">Name</td>
                        <td><input type="text" class="form-control input-sm required" name="shipto-name" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">Address</td>
                        <td><input type="text" class="form-control input-sm required" name="shipto-address" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">Address 2</td>
                        <td><input type="text" class="form-control input-sm" name="shipto-address2" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">Address 3</td>
                        <td><input type="text" class="form-control input-sm" name="shipto-address3" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">City</td>
                        <td><input type="text" class="form-control input-sm required" name="shipto-city" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">State</td>
                        <td><input type="text" class="form-control input-sm required" name="shipto-state" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">Zip</td>
                        <td><input type="text" class="form-control input-sm required" name="shipto-zip" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">Country</td>
                        <td>
                            <?php $countries = getcountries(); ?>
                            <select name="shipto-country" class="form-control input-sm">
                                <option value="USA">United States</option>
                                <?php foreach ($countries as $country) : ?>
                                    <option value="<?= $country['ccode']; ?>"><?= $country['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="control-label">Phone</td>
                        <td><input type="tel" class="form-control input-sm phone-input" name="shipto-phone" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">Ext.</td>
                        <td><input type="tel" class="form-control input-sm qty pull-right" name="shipto-ext" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">Fax</td>
                        <td><input type="tel" class="form-control input-sm phone-input" name="shipto-fax" value=""></td>
                    </tr>
                    <tr>
                        <td class="control-label">E-mail</td>
                        <td><input type="email" class="form-control input-sm" name="shipto-email" value=""></td>
                    </tr>
                </tbody>
           </table>
        </div> <!-- end shit to column -->
    </div> <!-- end top row-->
    <br>
    <div class="row">
        <div class="col-xs-6">
           <legend>Salesperson Information</legend>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-6">
            <table class="table table-striped table-bordered table-condensed">
                <tbody>
                    <tr>
                        <td class="control-label">Salesperson1</td>
                        <td>
                            <select name="salesperson1" class="form-control input-sm">
                                <?php foreach ($salespersoncodes as $salespersoncode) : ?>
                                    <option value="<?= $salespersonjson['data'][$salespersoncode]['splogin']; ?>"><?= $salespersoncode.' - '.$salespersonjson['data'][$salespersoncode]['spname']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="control-label">Salesperson2</td>
                        <td>
                            <select name="salesperson2" class="form-control input-sm">
                                <?php foreach ($salespersoncodes as $salespersoncode) : ?>
                                    <option value="<?= $salespersonjson['data'][$salespersoncode]['splogin']; ?>"><?= $salespersoncode.' - '.$salespersonjson['data'][$salespersoncode]['spname']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="control-label">Salesperson3</td>
                        <td>
                            <select name="salesperson3" class="form-control input-sm">
                                <?php foreach ($salespersoncodes as $salespersoncode) : ?>
                                    <option value="<?= $salespersonjson['data'][$salespersoncode]['splogin']; ?>"><?= $salespersoncode.' - '.$salespersonjson['data'][$salespersoncode]['spname']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="control-label">Price Code</td>
                        <td>
                            <select name="pricecode" class="form-control input-sm">
                                <?php foreach ($custpricecodes as $pricecode) : ?>
                                    <option value="<?= $pricecode; ?>"><?= $custpricejson['data'][$pricecode]['custpricecodedesc']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td class="control-label">Notes</td>
                        <td>
                            <textarea name="notes" rows="4" class="form-control input-sm"></textarea>
                        </td>
                    </tr>
                </tbody>
           </table>
        </div>
    </div> <!-- end price/notes row -->
    <button type="submit" class="btn btn-primary">Add New Customer</button>
</form>
