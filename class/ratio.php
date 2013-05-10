<?
class ratio {
	static function aspect($a, $b) {
        $gcd = self::gcd($a, $b);
		return [$a / $gcd, $b / $gcd];
    }			

	static function gcd($a, $b) {
		return $b == 0 ? $a : self::gcd($b, $a % $b);
	}

	static function find($antecedent, $ratio='1:1') {
		$parts = explode(':', $ratio);
		$ratio = $antecedent / $parts[0];
		return $ratio * $parts[1]; // consequent
	}
}
