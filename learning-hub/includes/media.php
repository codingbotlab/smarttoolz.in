<?php declare(strict_types=1);
function lh_course_image(string $category): string {
    $map=['computer-basics'=>'computer.svg','office-productivity'=>'office.svg','digital-skills'=>'digital.svg','graphic-design'=>'design.svg','web-development'=>'web.svg','programming'=>'code.svg','ai'=>'ai.svg','digital-marketing'=>'seo.svg','freelancing'=>'freelance.svg','business'=>'business.svg','advanced-tech'=>'tech.svg'];
    return '/learning-hub/assets/images/courses/'.($map[$category]??'tech.svg');
}
