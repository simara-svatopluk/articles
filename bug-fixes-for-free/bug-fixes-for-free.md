# Bugfixes for free

Forest work takeaway #1

## Forest work

I started to work as a forest worker recently, and I see bunch of parallels between SW engineering and forest work.
I'm a genuine contractor in the forest - I have a work that has to be done, and it's up to me to do it.
I'm paid for finished work - the money is agreed up front, and I don't get less or more.

When, how, with what tools - up to me. Up to me to wear proper protection. Up to me to get to the forest.

But most importantly - up to me to fix my own mistakes.

How it works?

If I fall a tree in a wrong direction, and I have to work for 2 more hours to get it out - I do it for free.
If I forget to plant a row of trees, I have to go back to the forest, and do it - for free.
If I ride the tractor over arranged pile of branches, I have to put them back - guess what? For free.

I have to correct my mistakes for free.

## Software World

So why do we pay engineers to fix *their own* bugs?

Where it leads to?

People are less responsible! Yeah, mistakes happen, I'll fix it.
Instead of - I made a mistake, I'll improve so it doesn't happen again.

People don't learn. I was told to work this way.
Instead of - I made a mistake, I have to check different architecture, test strategies, ... so it doesn't happen again.

People don't push innovation. I don't have power to push this language, test strategy, architecture.
Instead of - This approach is prone to errors, I'm changing it this way.

People don't search for the best tools. Nah, AI is just a language model, I'm the programmer.
Instead of - This tool allows me to be faster? Let's give it a try.

Of course - people in the higher level of hierarchy do these activities.
They care about improving processes because they feel the cost.

But engineers in the end of the line often don't. Often even resist. Often do it the way they were told to.

## Improve the Software World

To improve the SW world, we need to add some flavor, we cannot just tell engineers to do bugfixes for free.

### Authority to decide independently

A team must have the authority to decide independently - as I do in the forest.
I can only accept fixing bugs for free when I can decide my language, tools, architecture,
and maybe even a team composition.

### Requirements

Software is more often then not described very vaguely.

But when I know I'll fix bugs for free, I simply don't accept vague requirements.
I'll work hard to have everything crystal clear.

This goes hand to hand with authority to decide.
A team must have authority to not accept vague requirements.

### Let's do it

```
┌─────────────────────────┐ ┌──────────────────────┐ ┌─────────────────────────┐
│ Bugs are fixed          │ │ Authority over tools,│ │ Authority to reject     │
│ for free                │ │ architecture, team   │ │ vague requirements      │
└────────────┬────────────┘ └──────────┬───────────┘ └────────────┬────────────┘
             └─────────────────────────┼──────────────────────────┘
                                       v
              Team feels the cost of defects AND can act on it
                                       |
                                       v
            Prevention: better tests, tools, clearer requirements
                                       |
                                       v
                                  Fewer bugs
                                       |
                                       v
              Team owns its results, methods and risk
                    "a company inside a company"
```

## Clarification

I suggest that teams should fix *their own* bugs.
Not bugs that happened upstream, and the team has to firefight consequences.

Anyway, my experience is that if we spend more time clarifying requirements, it's quicker to deliver the software.

## Open points

* How to transfer projects between teams?
* What is a bug?