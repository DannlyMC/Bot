<?php


namespace Bot\commands;


use Bot\config\FilesManager;
use Bot\config\Utils;
use Bot\Main;
use Discord\Parts\Channel\Message;
use Discord\Parts\Embed\Embed;
use Discord\Parts\User\User;

class TopToxicCommand extends Command
{

    /**
     * @inheritDoc
     */
    function execute(?User $user, ?array $args, Message $message): bool
    {
        $file = FilesManager::getToxicFile()->getAll();

        if($file === []) {

            $embed = (new Embed(Main::getDiscord()))

                ->setAuthor("Top toxic", "https://icons.iconarchive.com/icons/yohproject/crayon-cute/128/box-empty-icon.png")
                ->setDescription("There is no one in the top toxic list of the server :test_tube: :")
                ->setColor(Utils::getColor(Utils::RED))
                ->setImage("https://cdn.discordapp.com/attachments/732798739269287966/806974978456158309/animated-line-image-0379.gif")
                ->setTimestamp(null);


            $message->channel->sendEmbed($embed);
            return true;

        }

        arsort($file);
        $top = 0;
        $messages = [];

        foreach ($file as $id => $amount) {

            $top++;
            if($top === 11) break;

            $user = $message->channel->guild->members->get("id", $id);
            $nicknames = ($user !== false ? $user->username . "#" . $user->discriminator : "Unavailable");

            $messages[] = "**Top (#{$top})** > ```{$nicknames} with an amount of {$amount} toxic points```\n";

        }

        $embed = (new Embed(Main::getDiscord()))

            ->setAuthor("Top Based", "https://icons.iconarchive.com/icons/thesquid.ink/free-flat-sample/128/cup-icon.png")
            ->setDescription("\nThis is the top toxic list of the server :test_tube: :\n\n" . implode(" ", $messages))
            ->setColor(Utils::getColor(Utils::GREEN))
            ->setImage("https://cdn.discordapp.com/attachments/732798739269287966/806974978456158309/animated-line-image-0379.gif")
            ->setTimestamp(null);


        $message->channel->sendEmbed($embed);
        return true;
    }
}