<?php

namespace Drupal\ide_helper;

class Utils {

  public static function extensionNameFromFqn(string $fqn): string {
    $parts = explode('\\', trim($fqn, '\\'));

    return $parts[1] ?? '_global';
  }

  public static function classNameFromFqn(string $fqn) {
    $parts = explode('\\', trim($fqn, '\\'));

    return end($parts);
  }


  public static function splitCamelCase(string $camelCase): array {
    return preg_split('/(?<=[a-z])(?=[A-Z])/', $camelCase);
  }

  public static function numOfWordMatches(string $className, string $interfaceName): int {
    $classNameWords = static::splitCamelCase($className);
    $interfaceNameWords = static::splitCamelCase($interfaceName);

    return count(array_intersect($classNameWords, $interfaceNameWords));
  }

  public static function serviceClass(array $service, array $allServices): string {
    if (!empty($service['class'])) {
      return $service['class'];
    }

    if (!empty($service['parent']) && isset($allServices[$service['parent']])) {
      return static::serviceClass($allServices[$service['parent']], $allServices);
    }

    return '';
  }

  public static function autodetectIdeaProjectRoot(string $cwd): string {
    $parts = explode('/', $cwd);

    while ($parts) {
      $dir = implode('/', $parts);
      if (is_dir("$dir/.idea")) {
        return $dir;
      }

      array_pop($parts);
    }

    return '';
  }

}
