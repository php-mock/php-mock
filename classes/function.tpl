namespace {namespace};

use malkusch\phpmock\MockFunctionHelper;

function {name}({signatureParameters})
{
    $arguments = [{bodyParameters}];

    $variadics = \array_slice(\func_get_args(), \count($arguments));
    $arguments = \array_merge($arguments, $variadics);

    return MockFunctionHelper::call(
        '{name}',
        '{fqfn}',
        $arguments
    );
}