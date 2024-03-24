<?php
namespace Core\Enum;
enum  LanguageEnum : int
{
    case Arabic =  1 ;
    case English = 2 ;
    case  French = 3 ;
    public function getLangSymbole(): string
    {
        return match($this)
        {
            LanguageEnum::Arabic => "ar",
            LanguageEnum::English => "en",
            LanguageEnum::French => "fr",
        };
    }
    public static function fromName(string $name): string
    {
        foreach (self::cases() as $status) {
            if( $name === $status->name ){
                return $status->getLangSymbole();
            }
        }
        throw new \ValueError("$name is not a valid backing value for enum " . self::class );
    }

}
