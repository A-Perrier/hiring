### For code quality, you can use some tools : which one and why (in a few words) ?

PHP-CS-Fixer (PHP Coding Standards Fixer) would be a good approach in a CI/CD because of its ability to fix the files
that require its action.

Also, PHPCS (PHP Code Sniffer) would be better integrated to an IDE, because it is designed to lint files and not fix them,
as PHP-CS-Fixer main purpose.

But there is some more, such as Psalm or PHP Mess Detector.
Having such linters installed on your IDE can be really helpful.


---


### You can consider to setup a ci/cd process : describe the necessary actions in a few words

Using for example GitHub Actions, we could setup a CI/CD process.
After creating at the project root a directory named `.github/workflows`, we're able to write YAML config files that'll 
describe to Github when and how to trigger some actions.

We could imagine a workflow which triggers on push on main branch.
This one would clone the project thanks to [`actions/checkout`](https://github.com/actions/checkout) action, then
run quality tools such these suggested above to finally, in case of success, deploy to production environment using
environment variables that would have been set up into the GitHub organization / repository.


---

### Other comments

It was the very first time for me to use Behat (and BDD), DDD, CQRS. I probably have done a lot of absurd mistakes in architecture from the point of view of long time users.
I documented myself a lot of all this, trying to complete this test the hard way (it would have been much easier in my comfort zone, but no pain no gain... :)).
I prefered using native PHP because I find it overkill to use a whole Symfony application for these simple features and found the exercice more challenging like this.
I don't really know what you'll think about the results of this test, but it was a lot of fun and I had much interest of these new concepts and I will look further quickly, so, whatever you judge this test, thank you for giving me the opportunity to reach new skills. :)
