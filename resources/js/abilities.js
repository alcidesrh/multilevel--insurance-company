import { AbilityBuilder, Ability, detectSubjectType } from "@casl/ability";

const subjectName = subject => {
    if (subject && typeof subject === "string") {
        return subject;
    }
    return detectSubjectType(subject);
};

const { can, rules } = new AbilityBuilder();

can("list", "Company");

const ability = new Ability(rules, {
    detectSubjectType: subject
});

const article = { __typename: "Article" };
ability.can("read", article); // true


export default AbilityBuilder.define({ subjectName }, can => {
    switch(type) {
        case 1:
          can('read', 'Post');
          break;
        case 2:
          can('read', 'Post');
          can(['update', 'delete'], 'Post');
          break;
        // Add more roles here
      }
  })

// export default function(type) {

//   AbilityBuilder.define(can => {
//     switch(type) {
//       case 1:
//         can('read', 'Post');
//         break;
//       case 2:
//         can('read', 'Post');
//         can(['update', 'delete'], 'Post');
//         break;
//       // Add more roles here
//     }
//   }
// }
