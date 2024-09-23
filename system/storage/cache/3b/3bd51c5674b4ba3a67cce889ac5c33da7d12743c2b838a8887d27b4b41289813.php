<?php

/* default/template/product/category.twig */
class __TwigTemplate_38008b053e44695c06d87a3fb7e04ddd0bc811a10edc88bf9c0e17cfe6198ce7 extends Twig_Template
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
        echo (isset($context["header"]) ? $context["header"] : null);
        echo "
<div id=\"product-category\" class=\"container\">
  <ul class=\"breadcrumb\">
    ";
        // line 4
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["breadcrumbs"]) ? $context["breadcrumbs"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["breadcrumb"]) {
            // line 5
            echo "    <li><a href=\"";
            echo $this->getAttribute($context["breadcrumb"], "href", array());
            echo "\">";
            echo $this->getAttribute($context["breadcrumb"], "text", array());
            echo "</a></li>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['breadcrumb'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 7
        echo "  </ul>
  <div class=\"row\">";
        // line 8
        echo (isset($context["column_left"]) ? $context["column_left"] : null);
        echo "
    ";
        // line 9
        if (((isset($context["column_left"]) ? $context["column_left"] : null) && (isset($context["column_right"]) ? $context["column_right"] : null))) {
            // line 10
            echo "    ";
            $context["class"] = "col-sm-6";
            // line 11
            echo "    ";
        } elseif (((isset($context["column_left"]) ? $context["column_left"] : null) || (isset($context["column_right"]) ? $context["column_right"] : null))) {
            // line 12
            echo "    ";
            $context["class"] = "col-sm-9";
            // line 13
            echo "    ";
        } else {
            // line 14
            echo "    ";
            $context["class"] = "col-sm-12";
            // line 15
            echo "    ";
        }
        // line 16
        echo "    <div id=\"content\" class=\"";
        echo (isset($context["class"]) ? $context["class"] : null);
        echo "\">";
        echo (isset($context["content_top"]) ? $context["content_top"] : null);
        echo "
      <h2>";
        // line 17
        echo (isset($context["heading_title"]) ? $context["heading_title"] : null);
        echo "</h2>
      ";
        // line 18
        if (((isset($context["thumb"]) ? $context["thumb"] : null) || (isset($context["description"]) ? $context["description"] : null))) {
            // line 19
            echo "      <div class=\"row\"> ";
            if ((isset($context["thumb"]) ? $context["thumb"] : null)) {
                // line 20
                echo "        <div class=\"col-sm-2\"><img src=\"";
                echo (isset($context["thumb"]) ? $context["thumb"] : null);
                echo "\" alt=\"";
                echo (isset($context["heading_title"]) ? $context["heading_title"] : null);
                echo "\" title=\"";
                echo (isset($context["heading_title"]) ? $context["heading_title"] : null);
                echo "\" class=\"img-thumbnail\" /></div>
        ";
            }
            // line 22
            echo "        ";
            if ((isset($context["description"]) ? $context["description"] : null)) {
                // line 23
                echo "        <div class=\"col-sm-10\">";
                echo (isset($context["description"]) ? $context["description"] : null);
                echo "</div>
        ";
            }
            // line 24
            echo "</div>
      <hr>
      ";
        }
        // line 27
        echo "      ";
        if ((isset($context["categories"]) ? $context["categories"] : null)) {
            // line 28
            echo "      <h3>";
            echo (isset($context["text_refine"]) ? $context["text_refine"] : null);
            echo "</h3>
      ";
            // line 29
            if ((twig_length_filter($this->env, (isset($context["categories"]) ? $context["categories"] : null)) <= 5)) {
                // line 30
                echo "      <div class=\"row\">
        <div class=\"col-sm-3\">
          <ul>
            ";
                // line 33
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable((isset($context["categories"]) ? $context["categories"] : null));
                foreach ($context['_seq'] as $context["_key"] => $context["category"]) {
                    // line 34
                    echo "            <li><a href=\"";
                    echo $this->getAttribute($context["category"], "href", array());
                    echo "\">";
                    echo $this->getAttribute($context["category"], "name", array());
                    echo "</a></li>
            ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['category'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 36
                echo "          </ul>
        </div>
      </div>
      ";
            } else {
                // line 40
                echo "      <div class=\"row\">";
                $context['_parent'] = $context;
                $context['_seq'] = twig_ensure_traversable(twig_array_batch((isset($context["categories"]) ? $context["categories"] : null), twig_round((twig_length_filter($this->env, (isset($context["categories"]) ? $context["categories"] : null)) / 4), 1, "ceil")));
                foreach ($context['_seq'] as $context["_key"] => $context["category"]) {
                    // line 41
                    echo "        <div class=\"col-sm-3\">
          <ul>
            ";
                    // line 43
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($context["category"]);
                    foreach ($context['_seq'] as $context["_key"] => $context["child"]) {
                        // line 44
                        echo "            <li><a href=\"";
                        echo $this->getAttribute($context["child"], "href", array());
                        echo "\">";
                        echo $this->getAttribute($context["child"], "name", array());
                        echo "</a></li>
            ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['child'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 46
                    echo "          </ul>
        </div>
        ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['category'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 48
                echo "</div>
      <br />
      ";
            }
            // line 51
            echo "      ";
        }
        // line 52
        echo "      ";
        if ((isset($context["products"]) ? $context["products"] : null)) {
            // line 53
            echo "      <div class=\"row\">
        <div class=\"col-md-2 col-sm-6 hidden-xs\">
          <div class=\"btn-group btn-group-sm\">
            <button type=\"button\" id=\"list-view\" class=\"btn btn-default\" data-toggle=\"tooltip\" title=\"";
            // line 56
            echo (isset($context["button_list"]) ? $context["button_list"] : null);
            echo "\"><i class=\"fa fa-th-list\"></i></button>
            <button type=\"button\" id=\"grid-view\" class=\"btn btn-default\" data-toggle=\"tooltip\" title=\"";
            // line 57
            echo (isset($context["button_grid"]) ? $context["button_grid"] : null);
            echo "\"><i class=\"fa fa-th\"></i></button>
          </div>
        </div>
        <div class=\"col-md-3 col-sm-6\">
          <div class=\"form-group\"><a href=\"";
            // line 61
            echo (isset($context["compare"]) ? $context["compare"] : null);
            echo "\" id=\"compare-total\" class=\"btn btn-link\">";
            echo (isset($context["text_compare"]) ? $context["text_compare"] : null);
            echo "</a></div>
        </div>
        <div class=\"col-md-4 col-xs-6\">
          <div class=\"form-group input-group input-group-sm\">
            <label class=\"input-group-addon\" for=\"input-sort\">";
            // line 65
            echo (isset($context["text_sort"]) ? $context["text_sort"] : null);
            echo "</label>
            <select id=\"input-sort\" class=\"form-control\" onchange=\"location = this.value;\">
              
              
              
              ";
            // line 70
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($context["sorts"]);
            foreach ($context['_seq'] as $context["_key"] => $context["sorts"]) {
                // line 71
                echo "              ";
                if (($this->getAttribute($context["sorts"], "value", array()) == sprintf("%s-%s", (isset($context["sort"]) ? $context["sort"] : null), (isset($context["order"]) ? $context["order"] : null)))) {
                    // line 72
                    echo "              
              
              
              <option value=\"";
                    // line 75
                    echo $this->getAttribute($context["sorts"], "href", array());
                    echo "\" selected=\"selected\">";
                    echo $this->getAttribute($context["sorts"], "text", array());
                    echo "</option>
              
              
              
              ";
                } else {
                    // line 80
                    echo "              
              
              
              <option value=\"";
                    // line 83
                    echo $this->getAttribute($context["sorts"], "href", array());
                    echo "\">";
                    echo $this->getAttribute($context["sorts"], "text", array());
                    echo "</option>
              
              
              
              ";
                }
                // line 88
                echo "              ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['sorts'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 89
            echo "            
            
            
            </select>
          </div>
        </div>
        <div class=\"col-md-3 col-xs-6\">
          <div class=\"form-group input-group input-group-sm\">
            <label class=\"input-group-addon\" for=\"input-limit\">";
            // line 97
            echo (isset($context["text_limit"]) ? $context["text_limit"] : null);
            echo "</label>
            <select id=\"input-limit\" class=\"form-control\" onchange=\"location = this.value;\">
              
              
              
              ";
            // line 102
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable($context["limits"]);
            foreach ($context['_seq'] as $context["_key"] => $context["limits"]) {
                // line 103
                echo "              ";
                if (($this->getAttribute($context["limits"], "value", array()) == (isset($context["limit"]) ? $context["limit"] : null))) {
                    // line 104
                    echo "              
              
              
              <option value=\"";
                    // line 107
                    echo $this->getAttribute($context["limits"], "href", array());
                    echo "\" selected=\"selected\">";
                    echo $this->getAttribute($context["limits"], "text", array());
                    echo "</option>
              
              
              
              ";
                } else {
                    // line 112
                    echo "              
              
              
              <option value=\"";
                    // line 115
                    echo $this->getAttribute($context["limits"], "href", array());
                    echo "\">";
                    echo $this->getAttribute($context["limits"], "text", array());
                    echo "</option>
              
              
              
              ";
                }
                // line 120
                echo "              ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['limits'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 121
            echo "            
            
            
            </select>
          </div>
        </div>
      </div>

            <form action=\"\" method=\"post\" enctype=\"multipart/form-data\" id=\"addmultiple\" >
            
      <div class=\"row\"> ";
            // line 131
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["products"]) ? $context["products"] : null));
            foreach ($context['_seq'] as $context["_key"] => $context["product"]) {
                // line 132
                echo "        <div class=\"product-layout product-list col-xs-12\">
          <div class=\"product-thumb\">
            <div class=\"image\"><a href=\"";
                // line 134
                echo $this->getAttribute($context["product"], "href", array());
                echo "\"><img src=\"";
                echo $this->getAttribute($context["product"], "thumb", array());
                echo "\" alt=\"";
                echo $this->getAttribute($context["product"], "name", array());
                echo "\" title=\"";
                echo $this->getAttribute($context["product"], "name", array());
                echo "\" class=\"img-responsive\" /></a></div>
            <div>
              <div class=\"caption\">
                <h4><a href=\"";
                // line 137
                echo $this->getAttribute($context["product"], "href", array());
                echo "\">";
                echo $this->getAttribute($context["product"], "name", array());
                echo "</a></h4>
                <p>";
                // line 138
                echo $this->getAttribute($context["product"], "description", array());
                echo "</p>
                ";
                // line 139
                if ($this->getAttribute($context["product"], "price", array())) {
                    // line 140
                    echo "                <p class=\"price\"> ";
                    if ( !$this->getAttribute($context["product"], "special", array())) {
                        // line 141
                        echo "                  ";
                        echo $this->getAttribute($context["product"], "price", array());
                        echo "
                  ";
                    } else {
                        // line 142
                        echo " <span class=\"price-new\">";
                        echo $this->getAttribute($context["product"], "special", array());
                        echo "</span> <span class=\"price-old\">";
                        echo $this->getAttribute($context["product"], "price", array());
                        echo "</span> ";
                    }
                    // line 143
                    echo "                  ";
                    if ($this->getAttribute($context["product"], "tax", array())) {
                        echo " <span class=\"price-tax\">";
                        echo (isset($context["text_tax"]) ? $context["text_tax"] : null);
                        echo " ";
                        echo $this->getAttribute($context["product"], "tax", array());
                        echo "</span> ";
                    }
                    echo " </p>
                ";
                }
                // line 145
                echo "                ";
                if ($this->getAttribute($context["product"], "rating", array())) {
                    // line 146
                    echo "                <div class=\"rating\"> ";
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable(range(1, 5));
                    foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
                        // line 147
                        echo "                  ";
                        if (($this->getAttribute($context["product"], "rating", array()) < $context["i"])) {
                            echo " <span class=\"fa fa-stack\"><i class=\"fa fa-star-o fa-stack-2x\"></i></span> ";
                        } else {
                            echo " <span class=\"fa fa-stack\"><i class=\"fa fa-star fa-stack-2x\"></i><i class=\"fa fa-star-o fa-stack-2x\"></i></span>";
                        }
                        // line 148
                        echo "                  ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    echo " </div>
                ";
                }
                // line 149
                echo " </div>
              
                         ";
                // line 151
                if ($this->getAttribute($context["product"], "options", array(), "array")) {
                    // line 152
                    echo "
    <div class=\"panel panel-default\">
  <div class=\"panel-heading\">
    <h4 class=\"panel-title\"><a href=\"#collapse-product";
                    // line 155
                    echo $this->getAttribute($context["product"], "product_id", array(), "array");
                    echo "\" class=\"accordion-toggle\" data-toggle=\"collapse\">";
                    echo (isset($context["text_option"]) ? $context["text_option"] : null);
                    echo "<i class=\"fa fa-caret-down\"></i></a></h4>
  </div>
  <div id=\"collapse-product";
                    // line 157
                    echo $this->getAttribute($context["product"], "product_id", array(), "array");
                    echo "\" class=\"panel-collapse collapse\">
    <div class=\"panel-body\">



            ";
                    // line 162
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["product"], "options", array(), "array"));
                    foreach ($context['_seq'] as $context["_key"] => $context["option"]) {
                        // line 163
                        echo "            ";
                        if (($this->getAttribute($context["option"], "type", array(), "array") == "select")) {
                            // line 164
                            echo "            <div class=\"form-group";
                            echo (($this->getAttribute($context["option"], "required", array(), "array")) ? (" required") : (""));
                            echo "\">
              <label class=\"control-label\" for=\"input-option";
                            // line 165
                            echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                            echo "\">";
                            echo $this->getAttribute($context["option"], "name", array(), "array");
                            echo "</label>
              <select name=\"option[";
                            // line 166
                            echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                            echo "]\" id=\"input-option";
                            echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                            echo "\" class=\"form-control\">
                <option value=\"\">";
                            // line 167
                            echo (isset($context["text_select"]) ? $context["text_select"] : null);
                            echo "</option>
                ";
                            // line 168
                            $context['_parent'] = $context;
                            $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["option"], "product_option_value", array(), "array"));
                            foreach ($context['_seq'] as $context["_key"] => $context["option_value"]) {
                                // line 169
                                echo "                <option value=\"";
                                echo $this->getAttribute($context["option_value"], "product_option_value_id", array(), "array");
                                echo "\">";
                                echo $this->getAttribute($context["option_value"], "name", array(), "array");
                                echo "
                ";
                                // line 170
                                if ($this->getAttribute($context["option_value"], "price", array(), "array")) {
                                    // line 171
                                    echo "                (";
                                    echo $this->getAttribute($context["option_value"], "price_prefix", array(), "array");
                                    echo $this->getAttribute($context["option_value"], "price", array(), "array");
                                    echo ")
                ";
                                }
                                // line 173
                                echo "                </option>
                ";
                            }
                            $_parent = $context['_parent'];
                            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['option_value'], $context['_parent'], $context['loop']);
                            $context = array_intersect_key($context, $_parent) + $_parent;
                            // line 175
                            echo "              </select>
            </div>
            ";
                        }
                        // line 178
                        echo "            ";
                        if (($this->getAttribute($context["option"], "type", array(), "array") == "radio")) {
                            // line 179
                            echo "            <div class=\"form-group";
                            echo (($this->getAttribute($context["option"], "required", array(), "array")) ? (" required") : (""));
                            echo "\">
              <label class=\"control-label\">";
                            // line 180
                            echo $this->getAttribute($context["option"], "name", array(), "array");
                            echo "</label>
              <div id=\"input-option";
                            // line 181
                            echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                            echo "\">
                ";
                            // line 182
                            $context['_parent'] = $context;
                            $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["option"], "product_option_value", array(), "array"));
                            foreach ($context['_seq'] as $context["_key"] => $context["option_value"]) {
                                // line 183
                                echo "                <div class=\"radio\">
                  <label>
                    <input type=\"radio\" name=\"option[";
                                // line 185
                                echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                                echo "]\" value=\"";
                                echo $this->getAttribute($context["option_value"], "product_option_value_id", array(), "array");
                                echo "\" />
                    ";
                                // line 186
                                echo $this->getAttribute($context["option_value"], "name", array(), "array");
                                echo "
                    ";
                                // line 187
                                if ($this->getAttribute($context["option_value"], "price", array(), "array")) {
                                    // line 188
                                    echo "                    (";
                                    echo $this->getAttribute($context["option_value"], "price_prefix", array(), "array");
                                    echo $this->getAttribute($context["option_value"], "price", array(), "array");
                                    echo ")
                    ";
                                }
                                // line 190
                                echo "                  </label>
                </div>
                ";
                            }
                            $_parent = $context['_parent'];
                            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['option_value'], $context['_parent'], $context['loop']);
                            $context = array_intersect_key($context, $_parent) + $_parent;
                            // line 193
                            echo "              </div>
            </div>
            ";
                        }
                        // line 196
                        echo "            ";
                        if (($this->getAttribute($context["option"], "type", array(), "array") == "checkbox")) {
                            // line 197
                            echo "            <div class=\"form-group";
                            echo (($this->getAttribute($context["option"], "required", array(), "array")) ? (" required") : (""));
                            echo "\">
              <label class=\"control-label\">";
                            // line 198
                            echo $this->getAttribute($context["option"], "name", array(), "array");
                            echo "</label>
              <div id=\"input-option";
                            // line 199
                            echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                            echo "\">
                ";
                            // line 200
                            $context['_parent'] = $context;
                            $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["option"], "product_option_value", array(), "array"));
                            foreach ($context['_seq'] as $context["_key"] => $context["option_value"]) {
                                // line 201
                                echo "                <div class=\"checkbox\">
                  <label>
                    <input type=\"checkbox\" name=\"option[";
                                // line 203
                                echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                                echo "][]\" value=\"";
                                echo $this->getAttribute($context["option_value"], "product_option_value_id", array(), "array");
                                echo "\" />
                    ";
                                // line 204
                                echo $this->getAttribute($context["option_value"], "name", array(), "array");
                                echo "
                    ";
                                // line 205
                                if ($this->getAttribute($context["option_value"], "price", array(), "array")) {
                                    // line 206
                                    echo "                    (";
                                    echo $this->getAttribute($context["option_value"], "price_prefix", array(), "array");
                                    echo $this->getAttribute($context["option_value"], "price", array(), "array");
                                    echo ")
                    ";
                                }
                                // line 208
                                echo "                  </label>
                </div>
                ";
                            }
                            $_parent = $context['_parent'];
                            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['option_value'], $context['_parent'], $context['loop']);
                            $context = array_intersect_key($context, $_parent) + $_parent;
                            // line 211
                            echo "              </div>
            </div>
            ";
                        }
                        // line 214
                        echo "            ";
                        if (($this->getAttribute($context["option"], "type", array(), "array") == "image")) {
                            // line 215
                            echo "            <div class=\"form-group";
                            echo (($this->getAttribute($context["option"], "required", array(), "array")) ? (" required") : (""));
                            echo "\">
              <label class=\"control-label\">";
                            // line 216
                            echo $this->getAttribute($context["option"], "name", array(), "array");
                            echo "</label>
              <div id=\"input-option";
                            // line 217
                            echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                            echo "\">
                ";
                            // line 218
                            $context['_parent'] = $context;
                            $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["option"], "product_option_value", array(), "array"));
                            foreach ($context['_seq'] as $context["_key"] => $context["option_value"]) {
                                // line 219
                                echo "                <div class=\"radio\">
                  <label>
                    <input type=\"radio\" name=\"option[";
                                // line 221
                                echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                                echo "]\" value=\"";
                                echo $this->getAttribute($context["option_value"], "product_option_value_id", array(), "array");
                                echo "\" />
                    <img src=\"";
                                // line 222
                                echo $this->getAttribute($context["option_value"], "image", array(), "array");
                                echo "\" alt=\"";
                                echo ((($this->getAttribute($context["option_value"], "name", array(), "array") . $this->getAttribute($context["option_value"], "price", array(), "array"))) ? (((" " . $this->getAttribute($context["option_value"], "price_prefix", array(), "array")) . $this->getAttribute($context["option_value"], "price", array(), "array"))) : (""));
                                echo "\" class=\"img-thumbnail\" /> ";
                                echo $this->getAttribute($context["option_value"], "name", array(), "array");
                                echo "
                    ";
                                // line 223
                                if ($this->getAttribute($context["option_value"], "price", array(), "array")) {
                                    // line 224
                                    echo "                    (";
                                    echo $this->getAttribute($context["option_value"], "price_prefix", array(), "array");
                                    echo $this->getAttribute($context["option_value"], "price", array(), "array");
                                    echo ")
                    ";
                                }
                                // line 226
                                echo "                  </label>
                </div>
                ";
                            }
                            $_parent = $context['_parent'];
                            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['option_value'], $context['_parent'], $context['loop']);
                            $context = array_intersect_key($context, $_parent) + $_parent;
                            // line 229
                            echo "              </div>
            </div>
            ";
                        }
                        // line 232
                        echo "            ";
                        if (($this->getAttribute($context["option"], "type", array(), "array") == "text")) {
                            // line 233
                            echo "            <div class=\"form-group";
                            echo (($this->getAttribute($context["option"], "required", array(), "array")) ? (" required") : (""));
                            echo "\">
              <label class=\"control-label\" for=\"input-option";
                            // line 234
                            echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                            echo "\">";
                            echo $this->getAttribute($context["option"], "name", array(), "array");
                            echo "</label>
              <input type=\"text\" name=\"option[";
                            // line 235
                            echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                            echo "]\" value=\"";
                            echo $this->getAttribute($context["option"], "value", array(), "array");
                            echo "\" placeholder=\"";
                            echo $this->getAttribute($context["option"], "name", array(), "array");
                            echo "\" id=\"input-option";
                            echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                            echo "\" class=\"form-control\" />
            </div>
            ";
                        }
                        // line 238
                        echo "            ";
                        if (($this->getAttribute($context["option"], "type", array(), "array") == "textarea")) {
                            // line 239
                            echo "            <div class=\"form-group";
                            echo (($this->getAttribute($context["option"], "required", array(), "array")) ? (" required") : (""));
                            echo "\">
              <label class=\"control-label\" for=\"input-option";
                            // line 240
                            echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                            echo "\">";
                            echo $this->getAttribute($context["option"], "name", array(), "array");
                            echo "</label>
              <textarea name=\"option[";
                            // line 241
                            echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                            echo "]\" rows=\"5\" placeholder=\"";
                            echo $this->getAttribute($context["option"], "name", array(), "array");
                            echo "\" id=\"input-option";
                            echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                            echo "\" class=\"form-control\">";
                            echo $this->getAttribute($context["option"], "value", array(), "array");
                            echo "</textarea>
            </div>
            ";
                        }
                        // line 244
                        echo "            ";
                        if (($this->getAttribute($context["option"], "type", array(), "array") == "file")) {
                            // line 245
                            echo "            <div class=\"form-group";
                            echo (($this->getAttribute($context["option"], "required", array(), "array")) ? (" required") : (""));
                            echo "\">
              <label class=\"control-label\">";
                            // line 246
                            echo $this->getAttribute($context["option"], "name", array(), "array");
                            echo "</label>
              <button type=\"button\" id=\"button-upload";
                            // line 247
                            echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                            echo "\" data-loading-text=\"";
                            echo (isset($context["text_loading"]) ? $context["text_loading"] : null);
                            echo "\" class=\"btn btn-default btn-block\"><i class=\"fa fa-upload\"></i> ";
                            echo (isset($context["button_upload"]) ? $context["button_upload"] : null);
                            echo "</button>
              <input type=\"hidden\" name=\"option[";
                            // line 248
                            echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                            echo "]\" value=\"\" id=\"input-option";
                            echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                            echo "\" />
            </div>
            ";
                        }
                        // line 251
                        echo "            ";
                        if (($this->getAttribute($context["option"], "type", array(), "array") == "date")) {
                            // line 252
                            echo "            <div class=\"form-group";
                            echo (($this->getAttribute($context["option"], "required", array(), "array")) ? (" required") : (""));
                            echo "\">
              <label class=\"control-label\" for=\"input-option";
                            // line 253
                            echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                            echo "\">";
                            echo $this->getAttribute($context["option"], "name", array(), "array");
                            echo "</label>
              <div class=\"input-group date\">
                <input type=\"text\" name=\"option[";
                            // line 255
                            echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                            echo "]\" value=\"";
                            echo $this->getAttribute($context["option"], "value", array(), "array");
                            echo "\" data-date-format=\"YYYY-MM-DD\" id=\"input-option";
                            echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                            echo "\" class=\"form-control\" />
                <span class=\"input-group-btn\">
                <button class=\"btn btn-default\" type=\"button\"><i class=\"fa fa-calendar\"></i></button>
                </span></div>
            </div>
            ";
                        }
                        // line 261
                        echo "            ";
                        if (($this->getAttribute($context["option"], "type", array(), "array") == "datetime")) {
                            // line 262
                            echo "            <div class=\"form-group";
                            echo (($this->getAttribute($context["option"], "required", array(), "array")) ? (" required") : (""));
                            echo "\">
              <label class=\"control-label\" for=\"input-option";
                            // line 263
                            echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                            echo "\">";
                            echo $this->getAttribute($context["option"], "name", array(), "array");
                            echo "</label>
              <div class=\"input-group datetime\">
                <input type=\"text\" name=\"option[";
                            // line 265
                            echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                            echo "]\" value=\"";
                            echo $this->getAttribute($context["option"], "value", array(), "array");
                            echo "\" data-date-format=\"YYYY-MM-DD HH:mm\" id=\"input-option";
                            echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                            echo "\" class=\"form-control\" />
                <span class=\"input-group-btn\">
                <button type=\"button\" class=\"btn btn-default\"><i class=\"fa fa-calendar\"></i></button>
                </span></div>
            </div>
            ";
                        }
                        // line 271
                        echo "            ";
                        if (($this->getAttribute($context["option"], "type", array(), "array") == "time")) {
                            // line 272
                            echo "            <div class=\"form-group";
                            echo (($this->getAttribute($context["option"], "required", array(), "array")) ? (" required") : (""));
                            echo "\">
              <label class=\"control-label\" for=\"input-option";
                            // line 273
                            echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                            echo "\">";
                            echo $this->getAttribute($context["option"], "name", array(), "array");
                            echo "</label>
              <div class=\"input-group time\">
                <input type=\"text\" name=\"option[";
                            // line 275
                            echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                            echo "]\" value=\"";
                            echo $this->getAttribute($context["option"], "value", array(), "array");
                            echo "\" data-format=\"HH:mm\" id=\"input-option";
                            echo $this->getAttribute($context["option"], "product_option_id", array(), "array");
                            echo "\" class=\"form-control\" />
                <span class=\"input-group-btn\">
                <button type=\"button\" class=\"btn btn-default\"><i class=\"fa fa-calendar\"></i></button>
                </span></div>
            </div>
            ";
                        }
                        // line 281
                        echo "            ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['option'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 282
                    echo "
            </div></div></div>
            ";
                }
                // line 285
                echo "            ";
                if ($this->getAttribute($context["product"], "recurrings", array(), "array")) {
                    // line 286
                    echo "            <div class=\"panel panel-default\">
  <div class=\"panel-heading\">
    <h4 class=\"panel-title\"><a href=\"#collapse-reccuring";
                    // line 288
                    echo $this->getAttribute($context["product"], "product_id", array(), "array");
                    echo "\" class=\"accordion-toggle\" data-toggle=\"collapse\">";
                    echo (isset($context["text_payment_recurring"]) ? $context["text_payment_recurring"] : null);
                    echo "<i class=\"fa fa-caret-down\"></i></a></h4>
  </div>
  <div id=\"collapse-reccuring";
                    // line 290
                    echo $this->getAttribute($context["product"], "product_id", array(), "array");
                    echo "\" class=\"panel-collapse collapse\">
    <div class=\"panel-body\">
            <div class=\"form-group required\">
              <select name=\"recurring_id";
                    // line 293
                    echo $this->getAttribute($context["product"], "product_id", array(), "array");
                    echo "\" class=\"form-control\">
                <option value=\"\">";
                    // line 294
                    echo (isset($context["text_select"]) ? $context["text_select"] : null);
                    echo "</option>
                ";
                    // line 295
                    $context['_parent'] = $context;
                    $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["product"], "recurrings", array(), "array"));
                    foreach ($context['_seq'] as $context["_key"] => $context["recurring"]) {
                        // line 296
                        echo "                <option value=\"";
                        echo $this->getAttribute($context["recurring"], "recurring_id", array(), "array");
                        echo "\">";
                        echo $this->getAttribute($context["recurring"], "name", array(), "array");
                        echo "</option>
                ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['_key'], $context['recurring'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 298
                    echo "              </select>
              <div class=\"help-block\" id=\"recurring-description";
                    // line 299
                    echo $this->getAttribute($context["product"], "product_id", array(), "array");
                    echo "\"></div>
            </div>
</div></div></div>
            ";
                }
                // line 303
                echo "                         <div class=\"input-group\">
                <div class=\"input-group-btn\">
                      <button type=\"button\" class=\"btn btn-default btn-number qtyminus\" field='";
                // line 305
                echo $this->getAttribute($context["product"], "product_id", array());
                echo "'>
                      <span class=\"glyphicon glyphicon-minus\"></span>
                      </button>
                </div>
                      <input type=\"hidden\" name=\"product_id[]\" value=\"";
                // line 309
                echo $this->getAttribute($context["product"], "product_id", array());
                echo "\"  />
                      <input type=\"text\" name=\"quantity[]\" class=\"form-control input-number\" value=\"0\" id=\"";
                // line 310
                echo $this->getAttribute($context["product"], "product_id", array());
                echo "\" />
                <div class=\"input-group-btn\">
                      <button type=\"button\" class=\"btn btn-default btn-number qtyplus\" field='";
                // line 312
                echo $this->getAttribute($context["product"], "product_id", array());
                echo "'>
                      <span class=\"glyphicon glyphicon-plus\"></span>
                      </button>
                      <button type=\"button\" class=\"btn btn-default\" data-toggle=\"tooltip\" title=\"";
                // line 315
                echo (isset($context["button_wishlist"]) ? $context["button_wishlist"] : null);
                echo "\" onclick=\"wishlist.add('";
                echo $this->getAttribute($context["product"], "product_id", array());
                echo "');\"><i class=\"fa fa-heart\"></i></button>
                      <button type=\"button\" class=\"btn btn-default\" data-toggle=\"tooltip\" title=\"";
                // line 316
                echo (isset($context["button_compare"]) ? $context["button_compare"] : null);
                echo "\" onclick=\"compare.add('";
                echo $this->getAttribute($context["product"], "product_id", array());
                echo "');\"><i class=\"fa fa-exchange\"></i></button>
               </div>
              </div>
           
            </div>
          </div>
        </div>
        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['product'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 323
            echo " </div>

               </form>
               <div class=\"cart\">
               <input type=\"button\" id=\"button-cart\" value=\"";
            // line 327
            echo (isset($context["button_cart"]) ? $context["button_cart"] : null);
            echo "\" onclick=\"addToCartMultiple();\" data-loading-text=\"";
            echo (isset($context["text_loading"]) ? $context["text_loading"] : null);
            echo "\" class=\"btn btn-primary btn-lg pull-right\" />
               </div>
            
      <div class=\"row\">
        <div class=\"col-sm-6 text-left\">";
            // line 331
            echo (isset($context["pagination"]) ? $context["pagination"] : null);
            echo "</div>
        <div class=\"col-sm-6 text-right\">";
            // line 332
            echo (isset($context["results"]) ? $context["results"] : null);
            echo "</div>
      </div>
      ";
        }
        // line 335
        echo "      ";
        if (( !(isset($context["categories"]) ? $context["categories"] : null) &&  !(isset($context["products"]) ? $context["products"] : null))) {
            // line 336
            echo "      <p>";
            echo (isset($context["text_empty"]) ? $context["text_empty"] : null);
            echo "</p>
      <div class=\"buttons\">
        <div class=\"pull-right\"><a href=\"";
            // line 338
            echo (isset($context["continue"]) ? $context["continue"] : null);
            echo "\" class=\"btn btn-primary\">";
            echo (isset($context["button_continue"]) ? $context["button_continue"] : null);
            echo "</a></div>
      </div>
      ";
        }
        // line 341
        echo "      ";
        echo (isset($context["content_bottom"]) ? $context["content_bottom"] : null);
        echo "</div>
    ";
        // line 342
        echo (isset($context["column_right"]) ? $context["column_right"] : null);
        echo "</div>
</div>

<script type=\"text/javascript\"><!--
\$('.date').datetimepicker({
\tlanguage: '";
        // line 347
        echo (isset($context["datepicker"]) ? $context["datepicker"] : null);
        echo "',
\tpickTime: false
});

\$('.datetime').datetimepicker({
\tlanguage: '";
        // line 352
        echo (isset($context["datepicker"]) ? $context["datepicker"] : null);
        echo "',
\tpickDate: true,
\tpickTime: true
});

\$('.time').datetimepicker({
\tlanguage: '";
        // line 358
        echo (isset($context["datepicker"]) ? $context["datepicker"] : null);
        echo "',
\tpickDate: false
});

\$(document).ready(function(){
    \$('.qtyplus').click(function(e){
        e.preventDefault();
        fieldName = \$(this).attr('field');
        var currentVal = parseInt(\$('input[id='+fieldName+']').val());
        if (!isNaN(currentVal)) {
            \$('input[id='+fieldName+']').val(currentVal + 1).change();
        } else {
            \$('input[id='+fieldName+']').val(0);
        }
    });
    \$(\".qtyminus\").click(function(e) {
        e.preventDefault();
        fieldName = \$(this).attr('field');
        var currentVal = parseInt(\$('input[id='+fieldName+']').val());
        if (!isNaN(currentVal) && currentVal > 0) {
            \$('input[id='+fieldName+']').val(currentVal - 1).change();
            } else {
            \$('input[id='+fieldName+']').val(0);
        }
    });
});


function addToCartMultiple() {

      \$.ajax({
\t\turl: 'index.php?route=checkout/cart/add_multiple',
\t\ttype: 'post',
\t\tdata: \$('#addmultiple').serialize(),
\t\tdataType: 'json',
\t\tbeforeSend: function() {
\t\t\t\$('#button-cart').button('loading');
\t\t},
\t\tcomplete: function() {
\t\t\t\$('#button-cart').button('reset');
\t\t},
\t\tsuccess: function(json) {
\t\t\t\$('.alert, .text-danger, .alert-danger').remove();
\t\t\t\$('.form-group').removeClass('has-error');
\t\t\t
\t\t\t
\t\t\tif (json['error_warning']) {
\t\t\t\t\$('.breadcrumb').after('<div class=\"alert alert-danger\">' + json['error_warning'] + '<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></div>');
\t\t\t\t\$('html, body').animate({ scrollTop: 0 }, 'slow');
\t\t      }

\t\t\tif (json['error']) {
\t\t\t\tif (json['error']['option']) {
\t\t\t\t\tfor (i in json['error']['option']) {
\t\t\t\t\t\tvar element = \$('#input-option' + i.replace('_', '-'));
\t\t\t\t\t\t
\t\t\t\t\t\tif (element.parent().hasClass('input-group')) {
\t\t\t\t\t\t\telement.parent().after('<div class=\"text-danger\">' + json['error']['option'][i] + '</div>');
\t\t\t\t\t\t} else {
\t\t\t\t\t\t\telement.after('<div class=\"text-danger\">' + json['error']['option'][i] + '</div>');
\t\t\t\t\t\t}
\t\t\t\t\t}
\t\t\t\t}
\t\t\t\tif (json['error']) {
\t\t\t\tif (json['error']['recurring']) {
\t\t\t\tfor (i in json['error']['recurring']) {
\t\t\t\t\t\$('select[name=\\'recurring_id' + i + '\\']').after('<div class=\"text-danger\">' + json['error']['recurring'][i] + '</div>');
\t\t\t\t\t}
\t\t\t\t}
\t\t\t\t}
\t\t
\t\t\t\t// Highlight any found errors
\t\t\t\t\$('.text-danger').parent().addClass('has-error');
\t\t\t\t\$('.text-danger').parent().closest('.panel-collapse').collapse('show');
\t\t\t}
\t\t\t
\t\t               \t\t
\t\t\tif (json['success']) {
\t\t\t\t\$('.breadcrumb').after('<div class=\"alert alert-success\">' + json['success'] + '<button type=\"button\" class=\"close\" data-dismiss=\"alert\">&times;</button></div>');

\t\t\t\t\$('#cart > button').html('<span id=\"cart-total\"><i class=\"fa fa-shopping-cart\"></i> ' + json['total'] + '</span>');

\t\t\t\t\$('html, body').animate({ scrollTop: 0 }, 'slow');

\t\t\t\t\$('#cart > ul').load('index.php?route=common/cart/info ul li');
\t\t\t}\t
\t\t}
\t});
}

\$('button[id^=\\'button-upload\\']').on('click', function() {
\tvar node = this;
\t
\t\$('#form-upload').remove();
\t
\t\$('body').prepend('<form enctype=\"multipart/form-data\" id=\"form-upload\" style=\"display: none;\"><input type=\"file\" name=\"file\" /></form>');
\t
\t\$('#form-upload input[name=\\'file\\']').trigger('click');
\t
\t\$('#form-upload input[name=\\'file\\']').on('change', function() {
\t\t\$.ajax({
\t\t\turl: 'index.php?route=tool/upload',
\t\t\ttype: 'post',
\t\t\tdataType: 'json',
\t\t\tdata: new FormData(\$(this).parent()[0]),
\t\t\tcache: false,
\t\t\tcontentType: false,
\t\t\tprocessData: false,
\t\t\tbeforeSend: function() {
\t\t\t\t\$(node).button('loading');
\t\t\t},
\t\t\tcomplete: function() {
\t\t\t\t\$(node).button('reset');
\t\t\t},
\t\t\tsuccess: function(json) {
\t\t\t\t\$('.text-danger').remove();
\t\t\t\t
\t\t\t\tif (json['error']) {
\t\t\t\t\t\$(node).parent().find('input').after('<div class=\"text-danger\">' + json['error'] + '</div>');
\t\t\t\t}
\t\t\t\t
\t\t\t\tif (json['success']) {
\t\t\t\t\talert(json['success']);
\t\t\t\t\t
\t\t\t\t\t\$(node).parent().find('input').attr('value', json['code']);
\t\t\t\t}
\t\t\t},
\t\t\terror: function(xhr, ajaxOptions, thrownError) {
\t\t\t\talert(thrownError + \"\\r\\n\" + xhr.statusText + \"\\r\\n\" + xhr.responseText);
\t\t\t}
\t\t});
\t});
});

\$('select[name^=\\'recurring_id\\']').change(function(){

var quantity = \$(this).closest('.product-layout').find('input[name=\\'quantity[]\\']').val();

var product_id = \$(this).closest('.product-layout').find('input[name=\\'product_id[]\\']').val();

var select = \$(this).val();

var dataString = 'product_id='+ product_id + '&quantity='+ quantity + '&recurring_id='+ select;

\t\$.ajax({
\t\turl: 'index.php?route=product/product/getRecurringDescription',
\t\ttype: 'post',
\t\tdata: dataString,
\t\tdataType: 'json',
\t\tbeforeSend: function() {
\t\t\t\$('#recurring-description' + product_id).html('');
\t\t},
\t\tsuccess: function(json) {
\t\t\t\$('.alert, .text-danger').remove();
\t\t\tif (json['success']) {
\t\t\t\t\$('#recurring-description' + product_id).html(json['success']);
\t\t\t}
\t\t}
\t});
});

\$('input[name=\"quantity[]\"]').change(function(){

\$('.alert, .text-danger').remove();

var select = \$(this).closest('.product-layout').find('select[name^=\\'recurring_id\\']').val();

if (select) {

var product_id = \$(this).closest('.product-layout').find('input[name=\\'product_id[]\\']').val();

var quantity = \$(this).val();

var dataString = 'product_id='+ product_id + '&quantity='+ quantity + '&recurring_id='+ select;

\t\$.ajax({
\t\turl: 'index.php?route=product/product/getRecurringDescription',
\t\ttype: 'post',
\t\tdata: dataString,
\t\tdataType: 'json',
\t\tbeforeSend: function() {
\t\t\t\$('#recurring-description' + product_id).html('');
\t\t},
\t\tsuccess: function(json) {
\t\t\tif (json['success']) {
\t\t\t\t\$('#recurring-description' + product_id).html(json['success']);
\t\t\t}
\t\t}
\t});
    }
});
//--></script>
            
";
        // line 551
        echo (isset($context["footer"]) ? $context["footer"] : null);
        echo " 
";
    }

    public function getTemplateName()
    {
        return "default/template/product/category.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  1221 => 551,  1025 => 358,  1016 => 352,  1008 => 347,  1000 => 342,  995 => 341,  987 => 338,  981 => 336,  978 => 335,  972 => 332,  968 => 331,  959 => 327,  953 => 323,  937 => 316,  931 => 315,  925 => 312,  920 => 310,  916 => 309,  909 => 305,  905 => 303,  898 => 299,  895 => 298,  884 => 296,  880 => 295,  876 => 294,  872 => 293,  866 => 290,  859 => 288,  855 => 286,  852 => 285,  847 => 282,  841 => 281,  828 => 275,  821 => 273,  816 => 272,  813 => 271,  800 => 265,  793 => 263,  788 => 262,  785 => 261,  772 => 255,  765 => 253,  760 => 252,  757 => 251,  749 => 248,  741 => 247,  737 => 246,  732 => 245,  729 => 244,  717 => 241,  711 => 240,  706 => 239,  703 => 238,  691 => 235,  685 => 234,  680 => 233,  677 => 232,  672 => 229,  664 => 226,  657 => 224,  655 => 223,  647 => 222,  641 => 221,  637 => 219,  633 => 218,  629 => 217,  625 => 216,  620 => 215,  617 => 214,  612 => 211,  604 => 208,  597 => 206,  595 => 205,  591 => 204,  585 => 203,  581 => 201,  577 => 200,  573 => 199,  569 => 198,  564 => 197,  561 => 196,  556 => 193,  548 => 190,  541 => 188,  539 => 187,  535 => 186,  529 => 185,  525 => 183,  521 => 182,  517 => 181,  513 => 180,  508 => 179,  505 => 178,  500 => 175,  493 => 173,  486 => 171,  484 => 170,  477 => 169,  473 => 168,  469 => 167,  463 => 166,  457 => 165,  452 => 164,  449 => 163,  445 => 162,  437 => 157,  430 => 155,  425 => 152,  423 => 151,  419 => 149,  410 => 148,  403 => 147,  398 => 146,  395 => 145,  383 => 143,  376 => 142,  370 => 141,  367 => 140,  365 => 139,  361 => 138,  355 => 137,  343 => 134,  339 => 132,  335 => 131,  323 => 121,  317 => 120,  307 => 115,  302 => 112,  292 => 107,  287 => 104,  284 => 103,  280 => 102,  272 => 97,  262 => 89,  256 => 88,  246 => 83,  241 => 80,  231 => 75,  226 => 72,  223 => 71,  219 => 70,  211 => 65,  202 => 61,  195 => 57,  191 => 56,  186 => 53,  183 => 52,  180 => 51,  175 => 48,  167 => 46,  156 => 44,  152 => 43,  148 => 41,  143 => 40,  137 => 36,  126 => 34,  122 => 33,  117 => 30,  115 => 29,  110 => 28,  107 => 27,  102 => 24,  96 => 23,  93 => 22,  83 => 20,  80 => 19,  78 => 18,  74 => 17,  67 => 16,  64 => 15,  61 => 14,  58 => 13,  55 => 12,  52 => 11,  49 => 10,  47 => 9,  43 => 8,  40 => 7,  29 => 5,  25 => 4,  19 => 1,);
    }
}
/* {{ header }}*/
/* <div id="product-category" class="container">*/
/*   <ul class="breadcrumb">*/
/*     {% for breadcrumb in breadcrumbs %}*/
/*     <li><a href="{{ breadcrumb.href }}">{{ breadcrumb.text }}</a></li>*/
/*     {% endfor %}*/
/*   </ul>*/
/*   <div class="row">{{ column_left }}*/
/*     {% if column_left and column_right %}*/
/*     {% set class = 'col-sm-6' %}*/
/*     {% elseif column_left or column_right %}*/
/*     {% set class = 'col-sm-9' %}*/
/*     {% else %}*/
/*     {% set class = 'col-sm-12' %}*/
/*     {% endif %}*/
/*     <div id="content" class="{{ class }}">{{ content_top }}*/
/*       <h2>{{ heading_title }}</h2>*/
/*       {% if thumb or description %}*/
/*       <div class="row"> {% if thumb %}*/
/*         <div class="col-sm-2"><img src="{{ thumb }}" alt="{{ heading_title }}" title="{{ heading_title }}" class="img-thumbnail" /></div>*/
/*         {% endif %}*/
/*         {% if description %}*/
/*         <div class="col-sm-10">{{ description }}</div>*/
/*         {% endif %}</div>*/
/*       <hr>*/
/*       {% endif %}*/
/*       {% if categories %}*/
/*       <h3>{{ text_refine }}</h3>*/
/*       {% if categories|length <= 5 %}*/
/*       <div class="row">*/
/*         <div class="col-sm-3">*/
/*           <ul>*/
/*             {% for category in categories %}*/
/*             <li><a href="{{ category.href }}">{{ category.name }}</a></li>*/
/*             {% endfor %}*/
/*           </ul>*/
/*         </div>*/
/*       </div>*/
/*       {% else %}*/
/*       <div class="row">{% for category in categories|batch((categories|length / 4)|round(1, 'ceil')) %}*/
/*         <div class="col-sm-3">*/
/*           <ul>*/
/*             {% for child in category %}*/
/*             <li><a href="{{ child.href }}">{{ child.name }}</a></li>*/
/*             {% endfor %}*/
/*           </ul>*/
/*         </div>*/
/*         {% endfor %}</div>*/
/*       <br />*/
/*       {% endif %}*/
/*       {% endif %}*/
/*       {% if products %}*/
/*       <div class="row">*/
/*         <div class="col-md-2 col-sm-6 hidden-xs">*/
/*           <div class="btn-group btn-group-sm">*/
/*             <button type="button" id="list-view" class="btn btn-default" data-toggle="tooltip" title="{{ button_list }}"><i class="fa fa-th-list"></i></button>*/
/*             <button type="button" id="grid-view" class="btn btn-default" data-toggle="tooltip" title="{{ button_grid }}"><i class="fa fa-th"></i></button>*/
/*           </div>*/
/*         </div>*/
/*         <div class="col-md-3 col-sm-6">*/
/*           <div class="form-group"><a href="{{ compare }}" id="compare-total" class="btn btn-link">{{ text_compare }}</a></div>*/
/*         </div>*/
/*         <div class="col-md-4 col-xs-6">*/
/*           <div class="form-group input-group input-group-sm">*/
/*             <label class="input-group-addon" for="input-sort">{{ text_sort }}</label>*/
/*             <select id="input-sort" class="form-control" onchange="location = this.value;">*/
/*               */
/*               */
/*               */
/*               {% for sorts in sorts %}*/
/*               {% if sorts.value == '%s-%s'|format(sort, order) %}*/
/*               */
/*               */
/*               */
/*               <option value="{{ sorts.href }}" selected="selected">{{ sorts.text }}</option>*/
/*               */
/*               */
/*               */
/*               {% else %}*/
/*               */
/*               */
/*               */
/*               <option value="{{ sorts.href }}">{{ sorts.text }}</option>*/
/*               */
/*               */
/*               */
/*               {% endif %}*/
/*               {% endfor %}*/
/*             */
/*             */
/*             */
/*             </select>*/
/*           </div>*/
/*         </div>*/
/*         <div class="col-md-3 col-xs-6">*/
/*           <div class="form-group input-group input-group-sm">*/
/*             <label class="input-group-addon" for="input-limit">{{ text_limit }}</label>*/
/*             <select id="input-limit" class="form-control" onchange="location = this.value;">*/
/*               */
/*               */
/*               */
/*               {% for limits in limits %}*/
/*               {% if limits.value == limit %}*/
/*               */
/*               */
/*               */
/*               <option value="{{ limits.href }}" selected="selected">{{ limits.text }}</option>*/
/*               */
/*               */
/*               */
/*               {% else %}*/
/*               */
/*               */
/*               */
/*               <option value="{{ limits.href }}">{{ limits.text }}</option>*/
/*               */
/*               */
/*               */
/*               {% endif %}*/
/*               {% endfor %}*/
/*             */
/*             */
/*             */
/*             </select>*/
/*           </div>*/
/*         </div>*/
/*       </div>*/
/* */
/*             <form action="" method="post" enctype="multipart/form-data" id="addmultiple" >*/
/*             */
/*       <div class="row"> {% for product in products %}*/
/*         <div class="product-layout product-list col-xs-12">*/
/*           <div class="product-thumb">*/
/*             <div class="image"><a href="{{ product.href }}"><img src="{{ product.thumb }}" alt="{{ product.name }}" title="{{ product.name }}" class="img-responsive" /></a></div>*/
/*             <div>*/
/*               <div class="caption">*/
/*                 <h4><a href="{{ product.href }}">{{ product.name }}</a></h4>*/
/*                 <p>{{ product.description }}</p>*/
/*                 {% if product.price %}*/
/*                 <p class="price"> {% if not product.special %}*/
/*                   {{ product.price }}*/
/*                   {% else %} <span class="price-new">{{ product.special }}</span> <span class="price-old">{{ product.price }}</span> {% endif %}*/
/*                   {% if product.tax %} <span class="price-tax">{{ text_tax }} {{ product.tax }}</span> {% endif %} </p>*/
/*                 {% endif %}*/
/*                 {% if product.rating %}*/
/*                 <div class="rating"> {% for i in 1..5 %}*/
/*                   {% if product.rating < i %} <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span> {% else %} <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>{% endif %}*/
/*                   {% endfor %} </div>*/
/*                 {% endif %} </div>*/
/*               */
/*                          {% if (product['options']) %}*/
/* */
/*     <div class="panel panel-default">*/
/*   <div class="panel-heading">*/
/*     <h4 class="panel-title"><a href="#collapse-product{{ product['product_id'] }}" class="accordion-toggle" data-toggle="collapse">{{ text_option }}<i class="fa fa-caret-down"></i></a></h4>*/
/*   </div>*/
/*   <div id="collapse-product{{ product['product_id'] }}" class="panel-collapse collapse">*/
/*     <div class="panel-body">*/
/* */
/* */
/* */
/*             {% for option in product['options'] %}*/
/*             {% if (option['type'] == 'select') %}*/
/*             <div class="form-group{{ option['required'] ? ' required' : '' }}">*/
/*               <label class="control-label" for="input-option{{ option['product_option_id'] }}">{{ option['name'] }}</label>*/
/*               <select name="option[{{ option['product_option_id'] }}]" id="input-option{{ option['product_option_id'] }}" class="form-control">*/
/*                 <option value="">{{ text_select }}</option>*/
/*                 {% for option_value in option['product_option_value'] %}*/
/*                 <option value="{{ option_value['product_option_value_id'] }}">{{ option_value['name'] }}*/
/*                 {% if (option_value['price']) %}*/
/*                 ({{ option_value['price_prefix'] }}{{ option_value['price'] }})*/
/*                 {% endif %}*/
/*                 </option>*/
/*                 {% endfor %}*/
/*               </select>*/
/*             </div>*/
/*             {% endif %}*/
/*             {% if (option['type'] == 'radio') %}*/
/*             <div class="form-group{{ option['required'] ? ' required' : '' }}">*/
/*               <label class="control-label">{{ option['name'] }}</label>*/
/*               <div id="input-option{{ option['product_option_id'] }}">*/
/*                 {% for option_value in option['product_option_value'] %}*/
/*                 <div class="radio">*/
/*                   <label>*/
/*                     <input type="radio" name="option[{{ option['product_option_id'] }}]" value="{{ option_value['product_option_value_id'] }}" />*/
/*                     {{ option_value['name'] }}*/
/*                     {% if (option_value['price']) %}*/
/*                     ({{ option_value['price_prefix'] }}{{ option_value['price'] }})*/
/*                     {% endif %}*/
/*                   </label>*/
/*                 </div>*/
/*                 {% endfor %}*/
/*               </div>*/
/*             </div>*/
/*             {% endif %}*/
/*             {% if (option['type'] == 'checkbox') %}*/
/*             <div class="form-group{{ option['required'] ? ' required' : '' }}">*/
/*               <label class="control-label">{{ option['name'] }}</label>*/
/*               <div id="input-option{{ option['product_option_id'] }}">*/
/*                 {% for option_value in option['product_option_value'] %}*/
/*                 <div class="checkbox">*/
/*                   <label>*/
/*                     <input type="checkbox" name="option[{{ option['product_option_id'] }}][]" value="{{ option_value['product_option_value_id'] }}" />*/
/*                     {{ option_value['name'] }}*/
/*                     {% if (option_value['price']) %}*/
/*                     ({{ option_value['price_prefix'] }}{{ option_value['price'] }})*/
/*                     {% endif %}*/
/*                   </label>*/
/*                 </div>*/
/*                 {% endfor %}*/
/*               </div>*/
/*             </div>*/
/*             {% endif %}*/
/*             {% if (option['type'] == 'image') %}*/
/*             <div class="form-group{{ option['required'] ? ' required' : '' }}">*/
/*               <label class="control-label">{{ option['name'] }}</label>*/
/*               <div id="input-option{{ option['product_option_id'] }}">*/
/*                 {% for option_value in option['product_option_value'] %}*/
/*                 <div class="radio">*/
/*                   <label>*/
/*                     <input type="radio" name="option[{{ option['product_option_id'] }}]" value="{{ option_value['product_option_value_id'] }}" />*/
/*                     <img src="{{ option_value['image'] }}" alt="{{ option_value['name'] ~ option_value['price'] ? ' ' ~ option_value['price_prefix'] ~ option_value['price'] : '' }}" class="img-thumbnail" /> {{ option_value['name'] }}*/
/*                     {% if (option_value['price']) %}*/
/*                     ({{ option_value['price_prefix'] }}{{ option_value['price'] }})*/
/*                     {% endif %}*/
/*                   </label>*/
/*                 </div>*/
/*                 {% endfor %}*/
/*               </div>*/
/*             </div>*/
/*             {% endif %}*/
/*             {% if (option['type'] == 'text') %}*/
/*             <div class="form-group{{ option['required'] ? ' required' : '' }}">*/
/*               <label class="control-label" for="input-option{{ option['product_option_id'] }}">{{ option['name'] }}</label>*/
/*               <input type="text" name="option[{{ option['product_option_id'] }}]" value="{{ option['value'] }}" placeholder="{{ option['name'] }}" id="input-option{{ option['product_option_id'] }}" class="form-control" />*/
/*             </div>*/
/*             {% endif %}*/
/*             {% if (option['type'] == 'textarea') %}*/
/*             <div class="form-group{{ option['required'] ? ' required' : '' }}">*/
/*               <label class="control-label" for="input-option{{ option['product_option_id'] }}">{{ option['name'] }}</label>*/
/*               <textarea name="option[{{ option['product_option_id'] }}]" rows="5" placeholder="{{ option['name'] }}" id="input-option{{ option['product_option_id'] }}" class="form-control">{{ option['value'] }}</textarea>*/
/*             </div>*/
/*             {% endif %}*/
/*             {% if (option['type'] == 'file') %}*/
/*             <div class="form-group{{ option['required'] ? ' required' : '' }}">*/
/*               <label class="control-label">{{ option['name'] }}</label>*/
/*               <button type="button" id="button-upload{{ option['product_option_id'] }}" data-loading-text="{{ text_loading }}" class="btn btn-default btn-block"><i class="fa fa-upload"></i> {{ button_upload }}</button>*/
/*               <input type="hidden" name="option[{{ option['product_option_id'] }}]" value="" id="input-option{{ option['product_option_id'] }}" />*/
/*             </div>*/
/*             {% endif %}*/
/*             {% if (option['type'] == 'date') %}*/
/*             <div class="form-group{{ option['required'] ? ' required' : '' }}">*/
/*               <label class="control-label" for="input-option{{ option['product_option_id'] }}">{{ option['name'] }}</label>*/
/*               <div class="input-group date">*/
/*                 <input type="text" name="option[{{ option['product_option_id'] }}]" value="{{ option['value'] }}" data-date-format="YYYY-MM-DD" id="input-option{{ option['product_option_id'] }}" class="form-control" />*/
/*                 <span class="input-group-btn">*/
/*                 <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>*/
/*                 </span></div>*/
/*             </div>*/
/*             {% endif %}*/
/*             {% if (option['type'] == 'datetime') %}*/
/*             <div class="form-group{{ option['required'] ? ' required' : '' }}">*/
/*               <label class="control-label" for="input-option{{ option['product_option_id'] }}">{{ option['name'] }}</label>*/
/*               <div class="input-group datetime">*/
/*                 <input type="text" name="option[{{ option['product_option_id'] }}]" value="{{ option['value'] }}" data-date-format="YYYY-MM-DD HH:mm" id="input-option{{ option['product_option_id'] }}" class="form-control" />*/
/*                 <span class="input-group-btn">*/
/*                 <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>*/
/*                 </span></div>*/
/*             </div>*/
/*             {% endif %}*/
/*             {% if (option['type'] == 'time') %}*/
/*             <div class="form-group{{ option['required'] ? ' required' : '' }}">*/
/*               <label class="control-label" for="input-option{{ option['product_option_id'] }}">{{ option['name'] }}</label>*/
/*               <div class="input-group time">*/
/*                 <input type="text" name="option[{{ option['product_option_id'] }}]" value="{{ option['value'] }}" data-format="HH:mm" id="input-option{{ option['product_option_id'] }}" class="form-control" />*/
/*                 <span class="input-group-btn">*/
/*                 <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>*/
/*                 </span></div>*/
/*             </div>*/
/*             {% endif %}*/
/*             {% endfor %}*/
/* */
/*             </div></div></div>*/
/*             {% endif %}*/
/*             {% if (product['recurrings']) %}*/
/*             <div class="panel panel-default">*/
/*   <div class="panel-heading">*/
/*     <h4 class="panel-title"><a href="#collapse-reccuring{{ product['product_id'] }}" class="accordion-toggle" data-toggle="collapse">{{ text_payment_recurring }}<i class="fa fa-caret-down"></i></a></h4>*/
/*   </div>*/
/*   <div id="collapse-reccuring{{ product['product_id'] }}" class="panel-collapse collapse">*/
/*     <div class="panel-body">*/
/*             <div class="form-group required">*/
/*               <select name="recurring_id{{ product['product_id'] }}" class="form-control">*/
/*                 <option value="">{{ text_select }}</option>*/
/*                 {% for recurring in product['recurrings'] %}*/
/*                 <option value="{{ recurring['recurring_id'] }}">{{ recurring['name'] }}</option>*/
/*                 {% endfor %}*/
/*               </select>*/
/*               <div class="help-block" id="recurring-description{{ product['product_id'] }}"></div>*/
/*             </div>*/
/* </div></div></div>*/
/*             {% endif %}*/
/*                          <div class="input-group">*/
/*                 <div class="input-group-btn">*/
/*                       <button type="button" class="btn btn-default btn-number qtyminus" field='{{ product.product_id }}'>*/
/*                       <span class="glyphicon glyphicon-minus"></span>*/
/*                       </button>*/
/*                 </div>*/
/*                       <input type="hidden" name="product_id[]" value="{{ product.product_id }}"  />*/
/*                       <input type="text" name="quantity[]" class="form-control input-number" value="0" id="{{ product.product_id }}" />*/
/*                 <div class="input-group-btn">*/
/*                       <button type="button" class="btn btn-default btn-number qtyplus" field='{{ product.product_id }}'>*/
/*                       <span class="glyphicon glyphicon-plus"></span>*/
/*                       </button>*/
/*                       <button type="button" class="btn btn-default" data-toggle="tooltip" title="{{ button_wishlist }}" onclick="wishlist.add('{{ product.product_id }}');"><i class="fa fa-heart"></i></button>*/
/*                       <button type="button" class="btn btn-default" data-toggle="tooltip" title="{{ button_compare }}" onclick="compare.add('{{ product.product_id }}');"><i class="fa fa-exchange"></i></button>*/
/*                </div>*/
/*               </div>*/
/*            */
/*             </div>*/
/*           </div>*/
/*         </div>*/
/*         {% endfor %} </div>*/
/* */
/*                </form>*/
/*                <div class="cart">*/
/*                <input type="button" id="button-cart" value="{{ button_cart }}" onclick="addToCartMultiple();" data-loading-text="{{ text_loading }}" class="btn btn-primary btn-lg pull-right" />*/
/*                </div>*/
/*             */
/*       <div class="row">*/
/*         <div class="col-sm-6 text-left">{{ pagination }}</div>*/
/*         <div class="col-sm-6 text-right">{{ results }}</div>*/
/*       </div>*/
/*       {% endif %}*/
/*       {% if not categories and not products %}*/
/*       <p>{{ text_empty }}</p>*/
/*       <div class="buttons">*/
/*         <div class="pull-right"><a href="{{ continue }}" class="btn btn-primary">{{ button_continue }}</a></div>*/
/*       </div>*/
/*       {% endif %}*/
/*       {{ content_bottom }}</div>*/
/*     {{ column_right }}</div>*/
/* </div>*/
/* */
/* <script type="text/javascript"><!--*/
/* $('.date').datetimepicker({*/
/* 	language: '{{ datepicker }}',*/
/* 	pickTime: false*/
/* });*/
/* */
/* $('.datetime').datetimepicker({*/
/* 	language: '{{ datepicker }}',*/
/* 	pickDate: true,*/
/* 	pickTime: true*/
/* });*/
/* */
/* $('.time').datetimepicker({*/
/* 	language: '{{ datepicker }}',*/
/* 	pickDate: false*/
/* });*/
/* */
/* $(document).ready(function(){*/
/*     $('.qtyplus').click(function(e){*/
/*         e.preventDefault();*/
/*         fieldName = $(this).attr('field');*/
/*         var currentVal = parseInt($('input[id='+fieldName+']').val());*/
/*         if (!isNaN(currentVal)) {*/
/*             $('input[id='+fieldName+']').val(currentVal + 1).change();*/
/*         } else {*/
/*             $('input[id='+fieldName+']').val(0);*/
/*         }*/
/*     });*/
/*     $(".qtyminus").click(function(e) {*/
/*         e.preventDefault();*/
/*         fieldName = $(this).attr('field');*/
/*         var currentVal = parseInt($('input[id='+fieldName+']').val());*/
/*         if (!isNaN(currentVal) && currentVal > 0) {*/
/*             $('input[id='+fieldName+']').val(currentVal - 1).change();*/
/*             } else {*/
/*             $('input[id='+fieldName+']').val(0);*/
/*         }*/
/*     });*/
/* });*/
/* */
/* */
/* function addToCartMultiple() {*/
/* */
/*       $.ajax({*/
/* 		url: 'index.php?route=checkout/cart/add_multiple',*/
/* 		type: 'post',*/
/* 		data: $('#addmultiple').serialize(),*/
/* 		dataType: 'json',*/
/* 		beforeSend: function() {*/
/* 			$('#button-cart').button('loading');*/
/* 		},*/
/* 		complete: function() {*/
/* 			$('#button-cart').button('reset');*/
/* 		},*/
/* 		success: function(json) {*/
/* 			$('.alert, .text-danger, .alert-danger').remove();*/
/* 			$('.form-group').removeClass('has-error');*/
/* 			*/
/* 			*/
/* 			if (json['error_warning']) {*/
/* 				$('.breadcrumb').after('<div class="alert alert-danger">' + json['error_warning'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');*/
/* 				$('html, body').animate({ scrollTop: 0 }, 'slow');*/
/* 		      }*/
/* */
/* 			if (json['error']) {*/
/* 				if (json['error']['option']) {*/
/* 					for (i in json['error']['option']) {*/
/* 						var element = $('#input-option' + i.replace('_', '-'));*/
/* 						*/
/* 						if (element.parent().hasClass('input-group')) {*/
/* 							element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');*/
/* 						} else {*/
/* 							element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');*/
/* 						}*/
/* 					}*/
/* 				}*/
/* 				if (json['error']) {*/
/* 				if (json['error']['recurring']) {*/
/* 				for (i in json['error']['recurring']) {*/
/* 					$('select[name=\'recurring_id' + i + '\']').after('<div class="text-danger">' + json['error']['recurring'][i] + '</div>');*/
/* 					}*/
/* 				}*/
/* 				}*/
/* 		*/
/* 				// Highlight any found errors*/
/* 				$('.text-danger').parent().addClass('has-error');*/
/* 				$('.text-danger').parent().closest('.panel-collapse').collapse('show');*/
/* 			}*/
/* 			*/
/* 		               		*/
/* 			if (json['success']) {*/
/* 				$('.breadcrumb').after('<div class="alert alert-success">' + json['success'] + '<button type="button" class="close" data-dismiss="alert">&times;</button></div>');*/
/* */
/* 				$('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');*/
/* */
/* 				$('html, body').animate({ scrollTop: 0 }, 'slow');*/
/* */
/* 				$('#cart > ul').load('index.php?route=common/cart/info ul li');*/
/* 			}	*/
/* 		}*/
/* 	});*/
/* }*/
/* */
/* $('button[id^=\'button-upload\']').on('click', function() {*/
/* 	var node = this;*/
/* 	*/
/* 	$('#form-upload').remove();*/
/* 	*/
/* 	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');*/
/* 	*/
/* 	$('#form-upload input[name=\'file\']').trigger('click');*/
/* 	*/
/* 	$('#form-upload input[name=\'file\']').on('change', function() {*/
/* 		$.ajax({*/
/* 			url: 'index.php?route=tool/upload',*/
/* 			type: 'post',*/
/* 			dataType: 'json',*/
/* 			data: new FormData($(this).parent()[0]),*/
/* 			cache: false,*/
/* 			contentType: false,*/
/* 			processData: false,*/
/* 			beforeSend: function() {*/
/* 				$(node).button('loading');*/
/* 			},*/
/* 			complete: function() {*/
/* 				$(node).button('reset');*/
/* 			},*/
/* 			success: function(json) {*/
/* 				$('.text-danger').remove();*/
/* 				*/
/* 				if (json['error']) {*/
/* 					$(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');*/
/* 				}*/
/* 				*/
/* 				if (json['success']) {*/
/* 					alert(json['success']);*/
/* 					*/
/* 					$(node).parent().find('input').attr('value', json['code']);*/
/* 				}*/
/* 			},*/
/* 			error: function(xhr, ajaxOptions, thrownError) {*/
/* 				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);*/
/* 			}*/
/* 		});*/
/* 	});*/
/* });*/
/* */
/* $('select[name^=\'recurring_id\']').change(function(){*/
/* */
/* var quantity = $(this).closest('.product-layout').find('input[name=\'quantity[]\']').val();*/
/* */
/* var product_id = $(this).closest('.product-layout').find('input[name=\'product_id[]\']').val();*/
/* */
/* var select = $(this).val();*/
/* */
/* var dataString = 'product_id='+ product_id + '&quantity='+ quantity + '&recurring_id='+ select;*/
/* */
/* 	$.ajax({*/
/* 		url: 'index.php?route=product/product/getRecurringDescription',*/
/* 		type: 'post',*/
/* 		data: dataString,*/
/* 		dataType: 'json',*/
/* 		beforeSend: function() {*/
/* 			$('#recurring-description' + product_id).html('');*/
/* 		},*/
/* 		success: function(json) {*/
/* 			$('.alert, .text-danger').remove();*/
/* 			if (json['success']) {*/
/* 				$('#recurring-description' + product_id).html(json['success']);*/
/* 			}*/
/* 		}*/
/* 	});*/
/* });*/
/* */
/* $('input[name="quantity[]"]').change(function(){*/
/* */
/* $('.alert, .text-danger').remove();*/
/* */
/* var select = $(this).closest('.product-layout').find('select[name^=\'recurring_id\']').val();*/
/* */
/* if (select) {*/
/* */
/* var product_id = $(this).closest('.product-layout').find('input[name=\'product_id[]\']').val();*/
/* */
/* var quantity = $(this).val();*/
/* */
/* var dataString = 'product_id='+ product_id + '&quantity='+ quantity + '&recurring_id='+ select;*/
/* */
/* 	$.ajax({*/
/* 		url: 'index.php?route=product/product/getRecurringDescription',*/
/* 		type: 'post',*/
/* 		data: dataString,*/
/* 		dataType: 'json',*/
/* 		beforeSend: function() {*/
/* 			$('#recurring-description' + product_id).html('');*/
/* 		},*/
/* 		success: function(json) {*/
/* 			if (json['success']) {*/
/* 				$('#recurring-description' + product_id).html(json['success']);*/
/* 			}*/
/* 		}*/
/* 	});*/
/*     }*/
/* });*/
/* //--></script>*/
/*             */
/* {{ footer }} */
/* */
