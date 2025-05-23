@extends('layouts.app')

    @section('content')

        <div class="container mx-auto p-4">
            <h1 class="text-2xl font-bold mb-6">ルート一覧（POST対応含む）</h1>

            <div class="mb-4">
                <button type="button"
                    class="text-blue-500 hover:underline"
                    onclick="toggleParamForm()">パラメータをセット</button>
            </div>

            <div id="form_params_container" class="hidden border rounded p-4 bg-gray-50">
                <form name="form_params" class="grid grid-cols-1 md:grid-cols-2 gap-4" onsubmit="return false;">
                    <!-- JavaScriptでここに項目が挿入される -->
                </form>
            </div>

            <table class="w-full border border-gray-300 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="border px-4 py-2">Name</th>
                        <th class="border px-4 py-2">Method</th>
                        <th class="border px-4 py-2">URI</th>
                        <th class="border px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($routes as $route)
                        <tr class="border-t hover:bg-gray-300">
                            <td class="border px-4 py-2 text-blue-600 font-mono">
                                {{ $route['name'] }}
                            </td>
                            <td class="border px-4 py-2 text-center text-xs">
                                {{ implode('|', $route['methods']) }}
                            </td>
                            <td class="border px-4 py-2 text-blue-600 font-mono">
                                {{ $route['uri'] }}
                            </td>
                            <td class="border px-4 py-2">
                                @if (in_array('GET', $route['methods']))
                                    <a href="{{ url($route['uri']) }}" target="_blank"
                                    class=" text-blue-500 hover:underline dynamic-link">GETリンクへ</a>
                                @elseif (in_array('POST', $route['methods']))
                                    <form action="{{ url($route['uri']) }}" method="POST" class="space-y-2 dynamic-form" target="_blank">
                                        @csrf
                                        @php
                                            $actionName = explode('/', $route['uri'])[0];
                                            $formConfig = $postForms[$actionName] ?? [];
                                        @endphp

                                        @foreach ($formConfig as $name => $info)
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700">{{ $name }}</label>
                                                @if ($info['type'] === 'input')
                                                    <input type="text" name="{{ $name }}" value="{{ $info['value'] ?? '' }}"
                                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" />
                                                @elseif ($info['type'] === 'select')
                                                    <select name="{{ $name }}"
                                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                                        @foreach ($info['options'] as $option)
                                                            <option value="{{ $option }}">{{ $option }}</option>
                                                        @endforeach
                                                    </select>
                                                @endif
                                            </div>
                                        @endforeach

                                        <button type="submit"
                                                class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded bg-blue-600 text-white hover:bg-blue-700">
                                            POST送信
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    @endsection


    <script type="text/javascript">

        // パラメータ用フォーム
        window.onload = function() {
            generateParamInputs();
        }

        function parameter_set()
        {
            let idValue = document.forms["form_params"]["param_id"].value;

            if (!idValue) {
                alert("id を入力してください");
                return;
            }

            // リンクの href を書き換える
            const links = document.querySelectorAll('a.dynamic-link');
            links.forEach(link => {
                const originalHref = link.getAttribute('href');
                if (originalHref.includes('{id}')) {
                    const newHref = originalHref.replace('{id}', encodeURIComponent(idValue));
                    link.setAttribute('href', newHref);
                }
            });

            // フォームの action を書き換える
            const forms = document.querySelectorAll('form.dynamic-form');
            forms.forEach(form => {
                const originalAction = form.getAttribute('action');
                if (originalAction.includes('{id}')) {
                    const newAction = originalAction.replace('{id}', encodeURIComponent(idValue));
                    form.setAttribute('action', newAction);
                }
            });
            
        }

        // パラメータからフォームを自動生成
        function generateParamInputs() {
            const paramForm = document.forms["form_params"];
            const targets = document.querySelectorAll('a.dynamic-link, form.dynamic-form');
            const paramNames = new Set();

            targets.forEach(el => {
                const url = el.tagName === 'A' ? el.getAttribute('href') : el.getAttribute('action');
                const matches = url.matchAll(/{([^}]+)}/g);
                for (const match of matches) {
                    paramNames.add(match[1]);
                }
            });
            console.log(targets);

            // 一度フォーム内をクリア
            paramForm.innerHTML = '';

            // 各パラメータ入力欄を追加
            paramNames.forEach(name => {
                const div = document.createElement('div');
                div.innerHTML = `
                    <label>${name}</label>
                    <input type="text" name="param_${name}" />
                `;
                paramForm.appendChild(div);
            });

            // セットボタン
            const button = document.createElement('button');
            button.type = "button";
            button.textContent = "パラメータセット";
            button.onclick = parameter_set;
            paramForm.appendChild(button);
        }

        function toggleParamForm() {
            const container = document.getElementById('form_params_container');
            container.classList.toggle('hidden');
        }
    </script>