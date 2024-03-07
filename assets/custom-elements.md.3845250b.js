import{_ as s,o as n,c as a,Q as e}from"./chunks/framework.733d63d2.js";const d=JSON.parse('{"title":"Custom element support - Element exporter plugin for Craft CMS","description":"Documentation for Element Exporter, a plugin for Craft CMS.","frontmatter":{"title":"Custom element support - Element exporter plugin for Craft CMS","layout":"doc","prev":false,"description":"Documentation for Element Exporter, a plugin for Craft CMS."},"headers":[],"relativePath":"custom-elements.md","filePath":"custom-elements.md"}'),l={name:"custom-elements.md"},p=e(`<h1 id="registering-support-for-new-element-types" tabindex="-1">Registering support for new element types <a class="header-anchor" href="#registering-support-for-new-element-types" aria-label="Permalink to &quot;Registering support for new element types&quot;">​</a></h1><h2 id="step-1-exportableelementtypemodel" tabindex="-1">Step 1: <code>ExportableElementTypeModel</code> <a class="header-anchor" href="#step-1-exportableelementtypemodel" aria-label="Permalink to &quot;Step 1: \`\`ExportableElementTypeModel\`\`&quot;">​</a></h2><p>Registering support for new element types is done throught a <code>ExportableElementTypeModel</code> model.</p><p>In the example below, we register support for Formie Submissions:</p><div class="language-php vp-adaptive-theme"><button title="Copy Code" class="copy"></button><span class="lang">php</span><pre class="shiki github-dark vp-code-dark"><code><span class="line"><span style="color:#F97583;">class</span><span style="color:#E1E4E8;"> </span><span style="color:#B392F0;">ExportableFormieSubmissionModel</span><span style="color:#E1E4E8;"> </span><span style="color:#F97583;">extends</span><span style="color:#E1E4E8;"> </span><span style="color:#B392F0;">ExportableElementTypeModel</span></span>
<span class="line"><span style="color:#E1E4E8;">{</span></span>
<span class="line"><span style="color:#E1E4E8;">    </span><span style="color:#6A737D;">/** @phpstan-ignore-next-line */</span></span>
<span class="line"><span style="color:#E1E4E8;">    </span><span style="color:#F97583;">public</span><span style="color:#E1E4E8;"> $elementType </span><span style="color:#F97583;">=</span><span style="color:#E1E4E8;"> </span><span style="color:#79B8FF;">Submission</span><span style="color:#F97583;">::class</span><span style="color:#E1E4E8;">;</span></span>
<span class="line"></span>
<span class="line"><span style="color:#E1E4E8;">    </span><span style="color:#F97583;">public</span><span style="color:#E1E4E8;"> </span><span style="color:#F97583;">string</span><span style="color:#E1E4E8;"> $elementLabel </span><span style="color:#F97583;">=</span><span style="color:#E1E4E8;"> </span><span style="color:#9ECBFF;">&quot;Formie Submissions&quot;</span><span style="color:#E1E4E8;">;</span></span>
<span class="line"></span>
<span class="line"><span style="color:#E1E4E8;">    </span><span style="color:#F97583;">public</span><span style="color:#E1E4E8;"> </span><span style="color:#F97583;">function</span><span style="color:#E1E4E8;"> </span><span style="color:#B392F0;">getGroup</span><span style="color:#E1E4E8;">()</span><span style="color:#F97583;">:</span><span style="color:#E1E4E8;"> </span><span style="color:#F97583;">array</span></span>
<span class="line"><span style="color:#E1E4E8;">    {</span></span>
<span class="line"><span style="color:#E1E4E8;">        </span><span style="color:#F97583;">return</span><span style="color:#E1E4E8;"> [</span></span>
<span class="line"><span style="color:#E1E4E8;">            </span><span style="color:#9ECBFF;">&quot;label&quot;</span><span style="color:#E1E4E8;"> </span><span style="color:#F97583;">=&gt;</span><span style="color:#E1E4E8;"> </span><span style="color:#9ECBFF;">&quot;Form&quot;</span><span style="color:#E1E4E8;">,</span></span>
<span class="line"><span style="color:#E1E4E8;">            </span><span style="color:#9ECBFF;">&quot;parameter&quot;</span><span style="color:#E1E4E8;"> </span><span style="color:#F97583;">=&gt;</span><span style="color:#E1E4E8;"> </span><span style="color:#9ECBFF;">&quot;formId&quot;</span><span style="color:#E1E4E8;">,</span></span>
<span class="line"><span style="color:#E1E4E8;">            </span><span style="color:#9ECBFF;">&quot;items&quot;</span><span style="color:#E1E4E8;"> </span><span style="color:#F97583;">=&gt;</span><span style="color:#E1E4E8;"> </span><span style="color:#79B8FF;">Formie</span><span style="color:#F97583;">::</span><span style="color:#B392F0;">getInstance</span><span style="color:#E1E4E8;">()</span><span style="color:#F97583;">-&gt;</span><span style="color:#B392F0;">getForms</span><span style="color:#E1E4E8;">()</span><span style="color:#F97583;">-&gt;</span><span style="color:#B392F0;">getAllForms</span><span style="color:#E1E4E8;">(), </span><span style="color:#6A737D;">// @phpstan-ignore-line</span></span>
<span class="line"><span style="color:#E1E4E8;">            </span><span style="color:#9ECBFF;">&quot;nameProperty&quot;</span><span style="color:#E1E4E8;"> </span><span style="color:#F97583;">=&gt;</span><span style="color:#E1E4E8;"> </span><span style="color:#9ECBFF;">&quot;title&quot;</span><span style="color:#E1E4E8;">,</span></span>
<span class="line"><span style="color:#E1E4E8;">        ];</span></span>
<span class="line"><span style="color:#E1E4E8;">    }</span></span>
<span class="line"></span>
<span class="line"><span style="color:#E1E4E8;">    </span><span style="color:#F97583;">public</span><span style="color:#E1E4E8;"> </span><span style="color:#F97583;">function</span><span style="color:#E1E4E8;"> </span><span style="color:#B392F0;">getSubGroup</span><span style="color:#E1E4E8;">()</span><span style="color:#F97583;">:</span><span style="color:#E1E4E8;"> </span><span style="color:#F97583;">bool</span><span style="color:#E1E4E8;">|</span><span style="color:#F97583;">array</span></span>
<span class="line"><span style="color:#E1E4E8;">    {</span></span>
<span class="line"><span style="color:#E1E4E8;">        </span><span style="color:#F97583;">return</span><span style="color:#E1E4E8;"> </span><span style="color:#79B8FF;">false</span><span style="color:#E1E4E8;">;</span></span>
<span class="line"><span style="color:#E1E4E8;">    }</span></span>
<span class="line"><span style="color:#E1E4E8;">}</span></span></code></pre><pre class="shiki github-light vp-code-light"><code><span class="line"><span style="color:#D73A49;">class</span><span style="color:#24292E;"> </span><span style="color:#6F42C1;">ExportableFormieSubmissionModel</span><span style="color:#24292E;"> </span><span style="color:#D73A49;">extends</span><span style="color:#24292E;"> </span><span style="color:#6F42C1;">ExportableElementTypeModel</span></span>
<span class="line"><span style="color:#24292E;">{</span></span>
<span class="line"><span style="color:#24292E;">    </span><span style="color:#6A737D;">/** @phpstan-ignore-next-line */</span></span>
<span class="line"><span style="color:#24292E;">    </span><span style="color:#D73A49;">public</span><span style="color:#24292E;"> $elementType </span><span style="color:#D73A49;">=</span><span style="color:#24292E;"> </span><span style="color:#005CC5;">Submission</span><span style="color:#D73A49;">::class</span><span style="color:#24292E;">;</span></span>
<span class="line"></span>
<span class="line"><span style="color:#24292E;">    </span><span style="color:#D73A49;">public</span><span style="color:#24292E;"> </span><span style="color:#D73A49;">string</span><span style="color:#24292E;"> $elementLabel </span><span style="color:#D73A49;">=</span><span style="color:#24292E;"> </span><span style="color:#032F62;">&quot;Formie Submissions&quot;</span><span style="color:#24292E;">;</span></span>
<span class="line"></span>
<span class="line"><span style="color:#24292E;">    </span><span style="color:#D73A49;">public</span><span style="color:#24292E;"> </span><span style="color:#D73A49;">function</span><span style="color:#24292E;"> </span><span style="color:#6F42C1;">getGroup</span><span style="color:#24292E;">()</span><span style="color:#D73A49;">:</span><span style="color:#24292E;"> </span><span style="color:#D73A49;">array</span></span>
<span class="line"><span style="color:#24292E;">    {</span></span>
<span class="line"><span style="color:#24292E;">        </span><span style="color:#D73A49;">return</span><span style="color:#24292E;"> [</span></span>
<span class="line"><span style="color:#24292E;">            </span><span style="color:#032F62;">&quot;label&quot;</span><span style="color:#24292E;"> </span><span style="color:#D73A49;">=&gt;</span><span style="color:#24292E;"> </span><span style="color:#032F62;">&quot;Form&quot;</span><span style="color:#24292E;">,</span></span>
<span class="line"><span style="color:#24292E;">            </span><span style="color:#032F62;">&quot;parameter&quot;</span><span style="color:#24292E;"> </span><span style="color:#D73A49;">=&gt;</span><span style="color:#24292E;"> </span><span style="color:#032F62;">&quot;formId&quot;</span><span style="color:#24292E;">,</span></span>
<span class="line"><span style="color:#24292E;">            </span><span style="color:#032F62;">&quot;items&quot;</span><span style="color:#24292E;"> </span><span style="color:#D73A49;">=&gt;</span><span style="color:#24292E;"> </span><span style="color:#005CC5;">Formie</span><span style="color:#D73A49;">::</span><span style="color:#6F42C1;">getInstance</span><span style="color:#24292E;">()</span><span style="color:#D73A49;">-&gt;</span><span style="color:#6F42C1;">getForms</span><span style="color:#24292E;">()</span><span style="color:#D73A49;">-&gt;</span><span style="color:#6F42C1;">getAllForms</span><span style="color:#24292E;">(), </span><span style="color:#6A737D;">// @phpstan-ignore-line</span></span>
<span class="line"><span style="color:#24292E;">            </span><span style="color:#032F62;">&quot;nameProperty&quot;</span><span style="color:#24292E;"> </span><span style="color:#D73A49;">=&gt;</span><span style="color:#24292E;"> </span><span style="color:#032F62;">&quot;title&quot;</span><span style="color:#24292E;">,</span></span>
<span class="line"><span style="color:#24292E;">        ];</span></span>
<span class="line"><span style="color:#24292E;">    }</span></span>
<span class="line"></span>
<span class="line"><span style="color:#24292E;">    </span><span style="color:#D73A49;">public</span><span style="color:#24292E;"> </span><span style="color:#D73A49;">function</span><span style="color:#24292E;"> </span><span style="color:#6F42C1;">getSubGroup</span><span style="color:#24292E;">()</span><span style="color:#D73A49;">:</span><span style="color:#24292E;"> </span><span style="color:#D73A49;">bool</span><span style="color:#24292E;">|</span><span style="color:#D73A49;">array</span></span>
<span class="line"><span style="color:#24292E;">    {</span></span>
<span class="line"><span style="color:#24292E;">        </span><span style="color:#D73A49;">return</span><span style="color:#24292E;"> </span><span style="color:#005CC5;">false</span><span style="color:#24292E;">;</span></span>
<span class="line"><span style="color:#24292E;">    }</span></span>
<span class="line"><span style="color:#24292E;">}</span></span></code></pre></div><p>In this model, we defined the following properties:</p><h3 id="elementtype" tabindex="-1"><code>elementType</code> <a class="header-anchor" href="#elementtype" aria-label="Permalink to &quot;\`\`elementType\`\`&quot;">​</a></h3><p>The element type&#39;s class you want to export</p><h3 id="elementlabel" tabindex="-1"><code>elementLabel</code> <a class="header-anchor" href="#elementlabel" aria-label="Permalink to &quot;\`\`elementLabel\`\`&quot;">​</a></h3><p>How you want the element type to be labeled when creating a new export</p><h3 id="getelementattributes" tabindex="-1"><code>getElementAttributes()</code> <a class="header-anchor" href="#getelementattributes" aria-label="Permalink to &quot;\`\`getElementAttributes()\`\`&quot;">​</a></h3><p>This function should return an array of <code>property =&gt; label</code> for default element attributes you want to make exportable.</p><h3 id="getgroup" tabindex="-1"><code>getGroup()</code> <a class="header-anchor" href="#getgroup" aria-label="Permalink to &quot;\`\`getGroup()\`\`&quot;">​</a></h3><p>In this function we defined how the elements are grouped or divided. This function should return an array with the following items:</p><ul><li><code>label</code>: what the grouping is called</li><li><code>parameter</code>: the property on the element that contains the id referencing the group</li><li><code>items</code>: this should call the function that gets all group options.</li><li><code>nameProperty</code>: under which property the name of the group can be found (name, title, etc)</li></ul><div class="warning custom-block"><p class="custom-block-title">Permissions for groups</p><p>Note that when registering a new element type, the <code>items</code> property should take into account the current user&#39;s permissions to see those elements.</p></div><hr><h2 id="step-2-register-our-model" tabindex="-1">Step 2: Register our model <a class="header-anchor" href="#step-2-register-our-model" aria-label="Permalink to &quot;Step 2: Register our model&quot;">​</a></h2><p>Once you&#39;ve created the model, you need to register it through the following event:</p><p>You add to the current supported types by adding <code>\\plugin\\namespace\\elements\\class =&gt; $model</code> to the current array.</p><div class="language-php vp-adaptive-theme"><button title="Copy Code" class="copy"></button><span class="lang">php</span><pre class="shiki github-dark vp-code-dark"><code><span class="line"><span style="color:#F97583;">use</span><span style="color:#E1E4E8;"> </span><span style="color:#79B8FF;">studioespresso\\exporter\\helpers\\ElementTypeHelper</span><span style="color:#E1E4E8;">;</span></span>
<span class="line"><span style="color:#F97583;">use</span><span style="color:#E1E4E8;"> </span><span style="color:#79B8FF;">studioespresso\\exporter\\events\\RegisterExportableElementTypes</span><span style="color:#E1E4E8;">;</span></span>
<span class="line"></span>
<span class="line"><span style="color:#E1E4E8;"> </span><span style="color:#79B8FF;">Event</span><span style="color:#F97583;">::</span><span style="color:#B392F0;">on</span><span style="color:#E1E4E8;">(</span></span>
<span class="line"><span style="color:#E1E4E8;">    </span><span style="color:#79B8FF;">ElementTypeHelper</span><span style="color:#F97583;">::class</span><span style="color:#E1E4E8;">,</span></span>
<span class="line"><span style="color:#E1E4E8;">    </span><span style="color:#79B8FF;">ElementTypeHelper</span><span style="color:#F97583;">::</span><span style="color:#79B8FF;">EVENT_REGISTER_EXPORTABLE_ELEMENT_TYPES</span><span style="color:#E1E4E8;">,</span></span>
<span class="line"><span style="color:#E1E4E8;">    </span><span style="color:#F97583;">function</span><span style="color:#E1E4E8;">(</span><span style="color:#79B8FF;">RegisterExportableElementTypes</span><span style="color:#E1E4E8;"> $event) {</span></span>
<span class="line"><span style="color:#E1E4E8;">        $model </span><span style="color:#F97583;">=</span><span style="color:#E1E4E8;"> </span><span style="color:#F97583;">new</span><span style="color:#E1E4E8;"> </span><span style="color:#79B8FF;">ExportableFormieSubmissionModel</span><span style="color:#E1E4E8;">();</span></span>
<span class="line"><span style="color:#E1E4E8;">        $event</span><span style="color:#F97583;">-&gt;</span><span style="color:#E1E4E8;">elementTypes </span><span style="color:#F97583;">=</span><span style="color:#E1E4E8;"> </span><span style="color:#79B8FF;">array_merge</span><span style="color:#E1E4E8;">($event</span><span style="color:#F97583;">-&gt;</span><span style="color:#E1E4E8;">elementTypes, [</span></span>
<span class="line"><span style="color:#E1E4E8;">            </span><span style="color:#79B8FF;">\\verbb\\formie\\elements\\Submission</span><span style="color:#F97583;">::class</span><span style="color:#E1E4E8;"> </span><span style="color:#F97583;">=&gt;</span><span style="color:#E1E4E8;"> $model,</span></span>
<span class="line"><span style="color:#E1E4E8;">        ]);</span></span>
<span class="line"><span style="color:#E1E4E8;">    }</span></span>
<span class="line"><span style="color:#E1E4E8;">);</span></span></code></pre><pre class="shiki github-light vp-code-light"><code><span class="line"><span style="color:#D73A49;">use</span><span style="color:#24292E;"> </span><span style="color:#005CC5;">studioespresso\\exporter\\helpers\\ElementTypeHelper</span><span style="color:#24292E;">;</span></span>
<span class="line"><span style="color:#D73A49;">use</span><span style="color:#24292E;"> </span><span style="color:#005CC5;">studioespresso\\exporter\\events\\RegisterExportableElementTypes</span><span style="color:#24292E;">;</span></span>
<span class="line"></span>
<span class="line"><span style="color:#24292E;"> </span><span style="color:#005CC5;">Event</span><span style="color:#D73A49;">::</span><span style="color:#6F42C1;">on</span><span style="color:#24292E;">(</span></span>
<span class="line"><span style="color:#24292E;">    </span><span style="color:#005CC5;">ElementTypeHelper</span><span style="color:#D73A49;">::class</span><span style="color:#24292E;">,</span></span>
<span class="line"><span style="color:#24292E;">    </span><span style="color:#005CC5;">ElementTypeHelper</span><span style="color:#D73A49;">::</span><span style="color:#005CC5;">EVENT_REGISTER_EXPORTABLE_ELEMENT_TYPES</span><span style="color:#24292E;">,</span></span>
<span class="line"><span style="color:#24292E;">    </span><span style="color:#D73A49;">function</span><span style="color:#24292E;">(</span><span style="color:#005CC5;">RegisterExportableElementTypes</span><span style="color:#24292E;"> $event) {</span></span>
<span class="line"><span style="color:#24292E;">        $model </span><span style="color:#D73A49;">=</span><span style="color:#24292E;"> </span><span style="color:#D73A49;">new</span><span style="color:#24292E;"> </span><span style="color:#005CC5;">ExportableFormieSubmissionModel</span><span style="color:#24292E;">();</span></span>
<span class="line"><span style="color:#24292E;">        $event</span><span style="color:#D73A49;">-&gt;</span><span style="color:#24292E;">elementTypes </span><span style="color:#D73A49;">=</span><span style="color:#24292E;"> </span><span style="color:#005CC5;">array_merge</span><span style="color:#24292E;">($event</span><span style="color:#D73A49;">-&gt;</span><span style="color:#24292E;">elementTypes, [</span></span>
<span class="line"><span style="color:#24292E;">            </span><span style="color:#005CC5;">\\verbb\\formie\\elements\\Submission</span><span style="color:#D73A49;">::class</span><span style="color:#24292E;"> </span><span style="color:#D73A49;">=&gt;</span><span style="color:#24292E;"> $model,</span></span>
<span class="line"><span style="color:#24292E;">        ]);</span></span>
<span class="line"><span style="color:#24292E;">    }</span></span>
<span class="line"><span style="color:#24292E;">);</span></span></code></pre></div>`,21),o=[p];function t(r,c,E,y,i,u){return n(),a("div",null,o)}const F=s(l,[["render",t]]);export{d as __pageData,F as default};
