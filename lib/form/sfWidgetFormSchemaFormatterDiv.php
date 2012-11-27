<?php
class sfWidgetFormSchemaFormatterDiv extends sfWidgetFormSchemaFormatter {
    protected
        $rowFormat = "<div class='form_row'>%label%%field%%help%%hidden_fields%%error%</div>",
        $helpFormat = "<div class='form_help'>%help%</div>",
        $errorRowFormat = "<div class='form_error'>%errors%</div>",
        $errorListFormatInARow = "<ul class='error_list'>%errors%</ul>",
        $errorRowFormatInARow = "<li>%error%</li>",
        $namedErrorRowFormatInARow = "<li>%name%: %error%</li>",
        $decoratorFormat = "<div class='form_container'>%content%</div>";
}
?>
