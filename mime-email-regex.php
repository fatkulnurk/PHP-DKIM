<?php
// CUT EMAILS (works)
// ([a-z-_.ąęśćźżńół]+)@((\w+)\.)+(\w+)+
// Cut email Jan
// (?:\G(?!\A)|From:|^From:).*?\K([a-z-_.ąęśćźżńół]+)@((\w+\.)|(\w+-\w+\.))+(\w+)+
// Cut email with names and lastnames
// (?:\G(?!\A)|From:|^From:).*?\s*\K([\w"\h]+[^\s,]+@[^,\s]+)
// with polish letters
// (?:\G(?!\A)|From:|^From).*?\s*\K([\wąęśćźżńół"\h]+[^\s,]+@[^,\s]+)

preg_match_all("|<[^>]+>(.*)</[^>]+>|U","<b>example: </b><div align=left>this is a test</div>",
    $out, PREG_PATTERN_ORDER);
echo $out[0][0] . ", " . $out[0][1] . "\n";
echo $out[1][0] . ", " . $out[1][1] . "\n";

?>

/* C++ search emails
vector<vector<string>> BreakermindSslServer::findEmails(const string& s)
{
    const string& reg_ex("(((\\w+([-\\._])+)+|())\\w+@(\\w+([-\\.])+)+\\w+)");
    regex rx(reg_ex, regex_constants::icase);
    vector<vector<string>> captured_groups;
    vector<string> captured_subgroups;
    const std::sregex_token_iterator end_i;
    for (std::sregex_token_iterator i(s.cbegin(), s.cend(), rx);
        i != end_i;
        ++i)
    {
        captured_subgroups.clear();
        string group = *i;
        smatch res;
        if(regex_search(group, res, rx))
        {
            for(unsigned i=0; i<res.size() ; i++)
                captured_subgroups.push_back(res[i]);

            if(captured_subgroups.size() > 0)
                captured_groups.push_back(captured_subgroups);
        }

    }
    captured_groups.push_back(captured_subgroups);
    return captured_groups;
}

// email validate
bool Mime::validEmail(std::string email){
    // std::regex pattern("[a-z0-9-_.ąęśćźżńół]+@[a-z0-9-_.ąęśćźżńół]+", std::regex_constants::icase);
    // std::regex pattern("^[a-z0-9-_.ąęśćźżńół]+@((\\w+([-.]))+(\\w+))+$", std::regex_constants::icase);
    // std::regex pattern("^((\w+([-._ąęśćźżńół])+)+|())(\w+([-._ąęśćźżńół])+)@((\w+([-.ąęśćźżńół])+)+(\w+))$", std::regex_constants::icase);

    std::regex pattern("(((\\w+([-\\._])+)+|())\\w+@(\\w+([-\\.])+)+\\w+)", std::regex_constants::icase);
    // try to match the string with the regular expression
    return std::regex_match(email, pattern);
}

std::string Mime::findTo(const string& s)
{
    std::regex rgx("(\\nTo: |^To: ))(.)+(\\n|\\r\\n|\\0)");
    std::smatch match;

    if (std::regex_search(s.begin(), s.end(), match, rgx))
    //std::cout << "match To: " << match[0] << '\n';
    return match[0];
}

bool Mime::Compare(vector<string> v1, vector<string> v2){
    if(v1.size() == v2.size()){
        return equal(v1.begin(), v1.end(), v2.begin());
    }else{
        return 0;
    }
}
*/
