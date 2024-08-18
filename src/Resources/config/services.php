<?php

declare(strict_types=1);

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use PhpLlm\LlmChain\Chat;
use PhpLlm\LlmChain\OpenAI\Model\Embeddings;
use PhpLlm\LlmChain\OpenAI\Model\Gpt;
use PhpLlm\LlmChain\OpenAI\Runtime;
use PhpLlm\LlmChain\RetrievalChain;
use PhpLlm\LlmChain\ToolBox\ParameterAnalyzer;
use PhpLlm\LlmChain\ToolBox\Registry;
use PhpLlm\LlmChain\ToolBox\ToolAnalyzer;
use PhpLlm\LlmChain\ToolChain;
use PhpLlm\LlmChain\OpenAI\Runtime\Azure as AzureRuntime;
use PhpLlm\LlmChain\OpenAI\Runtime\OpenAI as OpenAIRuntime;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->defaults()
            ->autowire()
            ->autoconfigure()

        // chains
        ->set(Chat::class)
        ->set(RetrievalChain::class)
        ->set(ToolChain::class)

        // runtimes
        ->set(AzureRuntime::class)
            ->abstract()
            ->args([
                '$baseUrl' => abstract_arg('Base URL for Azure API'),
                '$deployment' => abstract_arg('Deployment for Azure API'),
                '$apiVersion' => abstract_arg('API version for Azure API'),
                '$key' => abstract_arg('API key for Azure API'),
            ])
        ->set(OpenAIRuntime::class)
            ->abstract()
            ->args([
                '$apiKey' => abstract_arg('API key for OpenAI API'),
            ])

        // models
        ->set(Gpt::class)
            ->abstract()
            ->args([
                '$runtime' => service(Runtime::class),
            ])
        ->set(Embeddings::class)
            ->abstract()
            ->args([
                '$runtime' => service(Runtime::class),
            ])

        // tools
        ->set(Registry::class)
            ->args([
                '$tools' => tagged_iterator('llm_chain.tool'),
            ])
        ->set(ToolAnalyzer::class)
        ->set(ParameterAnalyzer::class)
    ;
};
