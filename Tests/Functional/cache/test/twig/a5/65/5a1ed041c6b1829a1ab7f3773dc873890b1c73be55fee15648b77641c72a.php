<?php

/* PaypalWebCheckoutBundle:Paypal:process.html.twig */
class __TwigTemplate_a5655a1ed041c6b1829a1ab7f3773dc873890b1c73be55fee15648b77641c72a extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"en\">
<head>
    <meta charset=\"UTF-8\">
    <title>Paypal Web checkout Redirect Form</title>
</head>
<body>
    ";
        // line 8
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["paypal_form"]) ? $context["paypal_form"] : $this->getContext($context, "paypal_form")), 'form_start', array("attr" => array("id" => "paypal_checkout_form")));
        echo "
        <input type=\"hidden\" name=\"cmd\" value=\"cart\">
        <input type=\"hidden\" name=\"upload\" value=\"1\">
        <input type=\"hidden\" name=\"lc\" value=\"";
        // line 11
        echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "request", array()), "locale", array()), "html", null, true);
        echo "\">
        ";
        // line 12
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["paypal_form"]) ? $context["paypal_form"] : $this->getContext($context, "paypal_form")), 'rest');
        echo "

    ";
        // line 14
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["paypal_form"]) ? $context["paypal_form"] : $this->getContext($context, "paypal_form")), 'form_end');
        echo "

    <script language=\"JavaScript\" type=\"text/javascript\">

        var form = document.forms[0];
        form.submit();

    </script>
</body>
</html>
";
    }

    public function getTemplateName()
    {
        return "PaypalWebCheckoutBundle:Paypal:process.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  43 => 14,  38 => 12,  34 => 11,  28 => 8,  19 => 1,);
    }
}
